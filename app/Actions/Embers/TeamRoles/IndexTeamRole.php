<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\IndexesTeamRoles;
use App\EmbersPermissionable;
use App\Models\TeamRole;

class IndexTeamRole implements IndexesTeamRoles
{
    use EmbersPermissionable;

    /**
     * Display all available Roles from user's current Team.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user)
    {
        $this->authorize($user);

        $roles = TeamRole::whereTeamId($user->current_team_id)->get();

        return $roles;
    }
}
