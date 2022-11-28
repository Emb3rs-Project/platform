<?php

namespace App\Contracts\Embers\Challenges;

use App\Models\Challenge;
use App\Models\User;

interface StoresChallenges
{
    /**
     * Validate and create a new Project.
     */
    public function store(User $user, array $input): Challenge;
}
