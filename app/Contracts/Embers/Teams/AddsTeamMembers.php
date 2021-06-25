<?php

namespace App\Contracts\Embers\Teams;

interface AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */
    public function add($user, $team, array $input);
}
