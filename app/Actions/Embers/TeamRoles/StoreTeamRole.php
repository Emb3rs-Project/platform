<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\StoresTeamRoles;
use App\EmbersPermissionable;
use App\HasEmbersPermissions;
use App\Models\TeamRole;
use App\Rules\Embers\TeamRole as EmbersTeamRole;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StoreTeamRole implements StoresTeamRoles
{
    use EmbersPermissionable;
    use HasEmbersPermissions;

    /**
     * Validate and create a new Role in user's current Team.
     *
     * @param  mixed  $user
     * @param  mixed  $user
     * @param  array  $input
     * @return Project
     */
    public function store($user, array $input)
    {
        $this->authorize($user);

        $this->validate($input);

        $role = $this->save($user, $input);

        return $role;
    }

    /**
     * Validate the create Role operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        Validator::make($input, [
            'role' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'distinct', 'max:255', new EmbersTeamRole],
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
        if ($user->currentTeam->user_id === $user->id) {
            // the user is not the team owner, deny him access
        }
        $role = TeamRole::create([
            'role' => $input['role'],
            'permissions' => $input['permissions']
        ]);


        // TODO: attach the role to user's current team
    }
}
