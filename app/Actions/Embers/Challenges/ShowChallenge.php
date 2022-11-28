<?php

namespace App\Actions\Embers\Challenges;

use App\Contracts\Embers\Challenges\ShowsChallenges;
use App\EmbersPermissionable;
use App\Models\Challenge;
use App\Models\User;

class ShowChallenge implements ShowsChallenges
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Project.
     *
     * @param \App\Models\User $user
     * @param int $id
     * @return Challenge
     */
    public function show(User $user, int $id): Challenge
    {
        $this->authorize($user);

        $challenge = Challenge::with([
            'goal',
            'restrictions',
            'participants'
        ])->findOrFail($id);

        return $challenge;
    }
}
