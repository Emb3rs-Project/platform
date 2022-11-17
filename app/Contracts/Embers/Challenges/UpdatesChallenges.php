<?php

namespace App\Contracts\Embers\Challenges;

use App\Models\Challenge;
use App\Models\User;

interface UpdatesChallenges
{
    /**
     * Validate and update an existing Project.
     */
    public function update(User $user, int $id, array $input): Challenge;
}
