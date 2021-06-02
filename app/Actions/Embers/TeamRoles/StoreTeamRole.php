<?php

namespace App\Actions\Embers\TeamRoles;

use App\Contracts\Embers\TeamRoles\StoresTeamRoles;
use App\EmbersPermissionable;
use App\Models\TeamRole;
use Illuminate\Support\Facades\Validator;

class StoreTeamRole implements StoresTeamRoles
{
    use EmbersPermissionable;

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
            'permissions.*' => ['required', 'string', 'max:255'],
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
        $role = TeamRole::create([
            'permissions' => $input['permissions']
        ]);

        // TODO: attach the role to user's current team
    }
}
