<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\ShowsProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\Simulation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ShowProject implements ShowsProjects
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Project.
     */
    public function show(User $user, int $id): Collection
    {
        $this->authorize($user);

        $project = Project::with([
            'location',
            'simulations',
            'simulations.simulationType',
            'simulations.simulationType.unit'
        ])->findOrFail($id);

        return $project;
    }
}
