<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\ShowsProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\User;

class ShowProject implements ShowsProjects
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Project.
     *
     * @param  \App\Models\User  $user
     * @param  int $id
     * @return \App\Models\Project
     */
    public function show(User $user, int $id): Project
    {
        $this->authorize($user);

        $project = Project::with([
            'simulations',
            'simulations.simulationMetadata'
        ])->findOrFail($id);

        return $project;
    }
}
