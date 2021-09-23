<?php

namespace App\Contracts\Embers\Projects;

use App\Models\Project;
use App\Models\User;

interface StoresProjects
{
    /**
     * Validate and create a new Project.
     */
    public function store(User $user, array $input): Project;
}
