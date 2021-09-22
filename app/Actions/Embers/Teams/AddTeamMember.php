<?php

namespace App\Actions\Embers\Teams;

use App\Contracts\Embers\Teams\AddsTeamMembers;
use App\Models\User;
use App\Notifications\MemberInvited;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;

class AddTeamMember implements AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */
    public function add($user, $team, array $input)
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);

        $this->validate($team, $input);

        $newTeamMember = Jetstream::findUserByEmailOrFail($input['email']);

        AddingTeamMember::dispatch($team, $newTeamMember);

        $team->users()->attach(
            $newTeamMember,
            ['team_role_id' => $input['team_role_id']]
        );

        $newTeamMember->notify(new MemberInvited($user, $team));

        TeamMemberAdded::dispatch($team, $newTeamMember);
    }

    /**
     * Validate the add member operation.
     *
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */
    protected function validate($team, array $input)
    {
        Validator::make([
            'email' => $input['email'],
            'team_role_id' => $input['team_role_id'],
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
            'team_role_id.required' => __('A role is required.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnTeam($team, $input['email'] = '')
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
     * @param  mixed  $team
     * @param  string  $email
     * @return \Closure
     */
    protected function ensureUserIsNotAlreadyOnTeam($team, string $email)
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf(
                $team->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the team.')
            );
        };
    }
}
