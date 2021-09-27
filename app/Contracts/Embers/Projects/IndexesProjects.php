<?php

namespace App\Contracts\Embers\Projects;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface IndexesProjects
{
    /**
     * Display all available Projects.
     */
    public function index(User $user): Collection;
}
