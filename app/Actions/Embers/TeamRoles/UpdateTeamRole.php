<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\UpdatesTeamRoles;
use App\EmbersPermissionable;
use App\Models\Permission;
use App\Models\TeamRole;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

        $this->validate($user, $input);

        $role = $this->save($project, $input);

        return $role;
    }

    /**
     * Validate the update Role operation.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function validate($user, array $input)
    {
        Validator::make($input, [
            'role' => [
                'filled',
                'string',
                'max:255',
                Rule::unique(TeamRole::class)->where(function ($query) use ($user) {
                    return $query->where('team_id', $user->current_team_id);
                })
            ],
            'permissions' => ['filled', 'array'],
            'permissions.*' => ['required', 'uuid', 'distinct', 'exists:permissions,friendly_id'],
        ])
        ->validate();
    }

    /**
     * Save the Role in the DB.
     *
     * @param  TeamRole  $role
     * @param  array  $input
     * @return Project
     */
    protected function save(TeamRole $role, array $input)
    {
        if (!empty($input['role'])) {
            $role->role = $input['role'];
        }

        if (!empty($input['permissions'])) {
            // Transform the permission friendly names to their coresponding actions
            foreach ($input['permissions'] as &$permission) {
                $permission = Permission::whereFriendlyId($permission)->first();
            }
            unset($permission);

            $role->permissions = Arr::pluck($input['permissions'], 'action');
        }

        $role->save();

        return $role;
    }
}
