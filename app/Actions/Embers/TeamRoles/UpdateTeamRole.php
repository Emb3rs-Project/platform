<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\UpdatesTeamRoles;
use App\EmbersPermissionable;
use App\Models\TeamRole;
use Illuminate\Support\Facades\Validator;

class UpdateTeamRole implements UpdatesTeamRoles
{
    use EmbersPermissionable;

    /**
     * Validate and update an existing Role from user's current Team.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return Instance
     */
    public function update($user, int $id, array $input)
    {
        $this->authorize($user);

        $project = TeamRole::whereTeamId($user->current_team_id)->findOrFail($id);

        $this->validate($input);

        $role = $this->save($project, $input);

        return $role;
    }

    /**
     * Validate the update Role operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        Validator::make($input, [
            'permissions.*' => ['filled', 'string', 'max:255']
        ])
        ->validate();
    }

    /**
     * Save the Role in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Project
     */
    protected function save(TeamRole $role, array $input)
    {
        if (!empty($input['permissions'])) {
            $role->permissions = $input['permissions'];
        }

        $role->save();

        return $role;
    }
}
