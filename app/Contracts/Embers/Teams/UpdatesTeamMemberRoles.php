<?php

namespace App\Contracts\Embers\Teams;

interface UpdatesTeamMemberRoles
{
    /**
    * Update the role for the given team member.
    *
    * @param  mixed  $user
    * @param  mixed  $team
    * @param  int  $teamMemberId
    * @param  int  $teamRoleId
    * @return void
    */
    public function update($user, $team, int $teamMemberId, int $teamRoleId);
}
