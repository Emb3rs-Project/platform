<?php

namespace App\Contracts\Embers\Teams;

use App\Models\Team;
use App\Models\User;

interface AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @param  array  $input
     * @return void
     */
    public function add(User $user, Team $team, array $input): void;
}
