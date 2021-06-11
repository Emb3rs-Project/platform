<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\StoresTeamRoles;
use App\EmbersPermissionable;
use App\Models\Permission;
use App\Models\Team;
use App\Models\TeamRole;
use App\Rules\Embers\TeamRole as TeamRoleRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreTeamRole implements StoresTeamRoles
{
    use EmbersPermissionable;

    /**
     * Validate and create a new Role in user's current Team.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Project
     */
    public function store($user, array $input)
    {
        $this->authorize($user);

        $this->validate($user, $input);

        $role = $this->save($user, $input);

        return $role;
    }

    /**
     * Validate the create Role operation.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function validate($user, array $input)
    {
        Validator::make($input, [
            'role' => [
                'required',
                'string',
                'max:255',
                Rule::unique(TeamRole::class)->where(function ($query) use ($user) {
                    return $query->where('team_id', $user->current_team_id);
                })
            ],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'distinct', 'max:255', new TeamRoleRule],
        ])
        ->validate();
    }

    /**
     * Save the Role in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function save($user, array $input)
    {
        // Transform the permission friendly names to their coresponding actions
        foreach ($input['permissions'] as &$permission) {
            $permission = Permission::whereFriendlyName($permission)->first();
        }
        unset($permission);

        $role = new TeamRole([
            'role' => $input['role'],
            'permissions' => Arr::pluck($input['permissions'], 'action')
        ]);

        $team = Team::find($user->current_team_id);

        $team->teamRoles()->save($role);

        return $role;
    }
}
