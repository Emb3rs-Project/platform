<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\CreatesTeamRoles;
use App\EmbersPermissionable;

class CreateTeamRole implements CreatesTeamRoles
{
    use EmbersPermissionable;

    /**
    * Display the necessary permissions for the creation of a Role for user's
    * current Team.
    *
    * @param  mixed  $user
    * @return mixed
    */
    public function create($user)
    {
        $this->authorize($user);

        // TODO: Get the permission names from action names that use the EmbersPermissionable trait

        return;
    }
}
