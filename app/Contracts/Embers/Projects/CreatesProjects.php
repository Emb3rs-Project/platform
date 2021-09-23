<?php

namespace App\Contracts\Embers\Projects;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface CreatesProjects
{
    /**
     * Display the necessary objects for the creation of a Project.
     */
    public function create(User $user): Collection;
}
