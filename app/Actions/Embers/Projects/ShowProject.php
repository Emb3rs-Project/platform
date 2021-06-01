<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\ShowsProjects;
use App\Models\Project;
use App\Models\Simulation;

class ShowProject implements ShowsProjects
{
    /**
     * Find and return an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-project'), 401);

        $project = Project::with(['location'])->findOrFail($id);

        $simulations = Simulation::with(['simulationType', 'simulationType.unit'])
            ->whereProjectId($project->id)
            ->get();

        return [
            $project,
            $simulations
        ];
    }
}
