<?php

namespace App\Contracts\Embers\TeamRoles;

interface ShowsTeamRoles
{
    /**
     * Display the given Role from user's current Team.
     *
     * @param  mixed  $user
     * @param  int  $id
     */
    public function show($user, int $id);
}
