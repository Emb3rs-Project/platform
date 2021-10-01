<?php

namespace App\Actions\Embers\Teams;

use App\Contracts\Embers\Teams\AddsTeamMembers;
use App\Events\Embers\AddingTeamMember;
use App\Events\Embers\TeamMemberAdded;
use App\Models\Team;
use App\Models\User;
use App\Notifications\Embers\MemberAdded;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;
use Laravel\Jetstream\Jetstream;

class AddTeamMember implements AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @param  array  $input
     * @return void
     */
    public function add(User $user, Team $team, array $input): void
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $input);

        $newTeamMember = Jetstream::findUserByEmailOrFail($input['email']);

        AddingTeamMember::dispatch($team, $newTeamMember, $input['team_role_id']);

        $team->users()->attach(
            $newTeamMember,
            ['team_role_id' => $input['team_role_id']]
        );

        $newTeamMember->notify(new MemberAdded($user, $team));

        TeamMemberAdded::dispatch($team, $newTeamMember, $input['team_role_id']);
    }

    /**
     * Validate the add member operation.
     *
     * @param  \App\Models\Team  $team
     * @param  array  $input
     * @return void
     */
    protected function validate(Team $team, array $input): void
    {
        Validator::make([
            'email' => $input['email'],
            'team_role_id' => $input['team_role_id'],
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
            'team_role_id.required' => __('A role is required.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $input['email'] ?? '')
        )->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array
     */
    protected function rules()
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users,email'],
            'team_role_id' => ['required', 'integer', 'numeric', 'exists:team_roles,id'],
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     *
     * @param  \App\Models\Team  $team
     * @param  string|null  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnTeam(Team $team, ?string $email = '')
    {
        return function (ValidationValidator $validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
