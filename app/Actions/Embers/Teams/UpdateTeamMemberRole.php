<?php

namespace App\Actions\Embers\Teams;

use App\Contracts\Embers\Teams\UpdatesTeamMemberRoles;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Events\TeamMemberUpdated;
use Laravel\Jetstream\Jetstream;

class UpdateTeamMemberRole implements UpdatesTeamMemberRoles
{
    /**
     * Update the role for the given team member.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  int  $teamMemberId
     * @param  int  $teamRoleId
     * @return void
     */
    public function update($user, $team, int $teamMemberId, int $teamRoleId)
    {
        Gate::forUser($user)->authorize('updateTeamMember', $team);

        Validator::make([
            'team_role_id' => $teamRoleId,
        ], [
            'team_role_id' => ['required', 'numeric', 'integer', 'exists:team_roles,id'],
        ])->validate();

        $team->users()->updateExistingPivot($teamMemberId, [
            'team_role_id' => $teamRoleId,
        ]);

        TeamMemberUpdated::dispatch($team->fresh(), Jetstream::findUserByIdOrFail($teamMemberId));
    }
}
