<?php

namespace App\Contracts\Embers\TeamRoles;

interface CreatesTeamRoles
{
    /**
     * Display the necessary permissions for the creation of a Role for user's
     * current Team.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user);
}
