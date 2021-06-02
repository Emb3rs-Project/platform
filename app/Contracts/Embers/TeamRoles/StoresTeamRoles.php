<?php

namespace App\Contracts\Embers\TeamRoles;

interface StoresTeamRoles
{
    /**
     * Validate and create a new Role in user's current Team.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store($user, array $input);
}
