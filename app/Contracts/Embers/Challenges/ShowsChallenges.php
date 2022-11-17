<?php

namespace App\Contracts\Embers\Challenges;

use App\Models\Challenge;
use App\Models\User;

interface ShowsChallenges
{
    /**
     * Display the given Projects.
     *
     * @param \App\Models\User $user
     * @param int $id
     * @return \App\Models\Challenge
     */
    public function show(User $user, int $id): Challenge;
}
