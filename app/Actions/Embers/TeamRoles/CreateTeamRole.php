<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\CreatesTeamRoles;
use App\EmbersPermissionable;
use App\HasEmbersPermissions;

class CreateTeamRole implements CreatesTeamRoles
{
    use EmbersPermissionable;
    use HasEmbersPermissions;

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

        $permissions = $this->getPermissionsKeyValue();

        return $permissions;
    }
}
