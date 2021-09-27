<?php

namespace App\Contracts\Embers\Projects;

use App\Models\Project;
use App\Models\User;

interface UpdatesProjects
{
    /**
     * Validate and update an existing Project.
     */
    public function update(User $user, int $id, array $input): Project;
}
