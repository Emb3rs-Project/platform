<?php

namespace App\Contracts\Embers\Projects;

use App\Models\User;

interface EditsProjects
{
    /**
     * Display the necessary objects for updating a given Project.
     */
    public function edit(User $user, int $id): array;
}
