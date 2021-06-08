<?php

namespace App\Contracts\Embers\Teams;

interface InvitesTeamMembers
{
    /**
     * Invite a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  string  $email
     * @param  int  $teamRoleId
     * @return void
     */
    public function invite($user, $team, string $email, int $teamRoleId);
}
