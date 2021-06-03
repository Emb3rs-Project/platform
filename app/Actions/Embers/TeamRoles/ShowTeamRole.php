<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\ShowsTeamRoles;
use App\EmbersPermissionable;
use App\Models\TeamRole;

class ShowTeamRole implements ShowsTeamRoles
{
    use EmbersPermissionable;

    /**
     * Display the given Role from user's current Team.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
    {
        $this->authorize($user);

        $role = TeamRole::whereTeamId($user->current_team_id)->findOrFail($id);

        return $role;
    }
}
