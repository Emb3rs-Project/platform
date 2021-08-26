<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\ShowsProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\Simulation;

class ShowProject implements ShowsProjects
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
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
