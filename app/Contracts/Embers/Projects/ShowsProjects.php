<?php

namespace App\Contracts\Embers\Projects;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ShowsProjects
{
    /**
     * Display the given Projects.
     */
    public function show(User $user, int $id): Collection;
}
