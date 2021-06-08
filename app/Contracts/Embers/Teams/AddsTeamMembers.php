<?php

namespace App\Contracts\Embers\Teams;

interface AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  string  $email
     * @param  int  $teamRoleId
     * @return void
     */
    public function add($user, $team, string $email, int $teamRoleId);
}
