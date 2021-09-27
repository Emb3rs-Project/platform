<?php

namespace App\Contracts\Embers\Projects;

use App\Models\User;

interface DestroysProjects
{
    /**
     * Delete an existing Project.
     */
    public function destroy(User $user, int $id): void;
}
