<?php

namespace App\Contracts\Embers\TeamRoles;

interface EditsTeamRoles
{
    /**
     * Display the necessary permissions for updating a given Role in user's
     * current Team.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function edit($user, int $id);
}
