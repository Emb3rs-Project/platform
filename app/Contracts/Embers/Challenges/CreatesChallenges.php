<?php

namespace App\Contracts\Embers\Challenges;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CreatesChallenges
{
    /**
     * Display the necessary objects for the creation of a Project.
     */
    public function create(User $user): Collection;
}
