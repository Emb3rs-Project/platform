<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\EditsTeamRoles;
use App\EmbersPermissionable;
use App\HasEmbersPermissions;
use App\Models\TeamRole;

class EditTeamRole implements EditsTeamRoles
{
    use EmbersPermissionable;
    use HasEmbersPermissions;

    /**
     * Display the necessary permissions for updating a given Role in user's
     * current Team.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function edit($user, int $id)
    {
        $this->authorize($user);

        $role = TeamRole::query()->whereTeamId($user->current_team_id)->findOrFail($id);

        $permissions = $this->getFriendlyPermissions();

        return [
            $role,
            $permissions
        ];
    }
}
