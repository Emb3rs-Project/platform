<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\ShowsProjects;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;

class ShowProject implements ShowsProjects
{
    /**
     * Find and return an existing Project.
     *
     * @param  int  $id
     * @return mixed
     */
    public function show(int $id)
    {
        $project = Project::with(['location'])->findOrFail($id);

        Gate::authorize('view', $project);

        $simulations = Simulation::with(['simulationType', 'simulationType.unit'])
            ->whereProjectId($project->id)
            ->get();

        return [
            $project,
            $simulations
        ];
    }
}
