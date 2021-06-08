<?php

namespace App\Contracts\Embers\TeamRoles;

interface IndexesTeamRoles
{
    /**
     * Display all available Roles from user's current Team.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user);
}
