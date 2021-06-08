<?php

namespace App\Contracts\Embers\TeamRoles;

interface UpdatesTeamRoles
{
    /**
     * Validate and update an existing Role from user's current Team.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return mixed
     */
    public function update($user, int $id, array $input);
}
