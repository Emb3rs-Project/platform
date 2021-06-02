<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\DestroysTeamRoles;
use App\EmbersPermissionable;
use App\Models\TeamRole;

class DestoryTeamRole implements DestroysTeamRoles
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

        TeamRole::destroy($role->id);
    }
}
