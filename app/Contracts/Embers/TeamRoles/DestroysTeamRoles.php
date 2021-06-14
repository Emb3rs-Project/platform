<?php

namespace App\Contracts\Embers\TeamRoles;

interface DestroysTeamRoles
{
    /**
     * Delete an existing Role from user's current Team.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id);
}
