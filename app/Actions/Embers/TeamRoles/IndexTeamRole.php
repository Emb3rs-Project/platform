<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\IndexesTeamRoles;
use App\EmbersPermissionable;
use App\Models\Permission;
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

        $roles = TeamRole::query()->whereTeamId($user->current_team_id)->get();

        $roles->transform(function ($role) {
            $permissions = $role->permissions;

            $friendlyPermissions = [];

            foreach ($permissions as $action) {
                $permission = Permission::query()->whereAction($action)->first();

                array_push($friendlyPermissions, $permission->friendly_name);
            }

            $role->permissions = $friendlyPermissions;

            return $role;
        });

        return $roles;
    }
}
