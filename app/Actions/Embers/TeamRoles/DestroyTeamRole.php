<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\DestroysTeamRoles;
use App\EmbersPermissionable;
use App\Models\Membership;
use App\Models\TeamRole;
use Illuminate\Validation\ValidationException;

class DestroyTeamRole implements DestroysTeamRoles
{
    use EmbersPermissionable;

    /**
      * Delete an existing Role from user's current Team.
      *
      * @param  mixed  $user
      * @param  int  $id
      * @param  array  $input
      * @return void
      */
    public function destroy($user, int $id)
    {
        $this->authorize($user);

        $role = TeamRole::whereTeamId($user->current_team_id)->findOrFail($id);

        $this->ensureRoleIsNotInUse($role);

        TeamRole::destroy($role->id);
    }

    /**
     * Ensure the Role that is about to be deleted is not in use
     *
     * @param  mixed  role
     * @return void
     */
    private function ensureRoleIsNotInUse($role): void
    {
        $usersWithThatRole = Membership::whereTeamRoleId($role->id)->get();

        if ($usersWithThatRole->isNotEmpty()) {
            throw ValidationException::withMessages([
                'role' => [__('You may not delete a role that is in use.')],
            ])->errorBag('removeTeamRole');
        }
    }
}
