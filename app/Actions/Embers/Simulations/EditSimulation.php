<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\EditsSimulations;
use App\Models\Location;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;

class EditSimulation implements EditsSimulations
{
    /**
     * Display the necessary objects for updating a given Project.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function edit(int $projectId, int $simulationId)
    {
        $project = Project::with(['location'])->findOrFail($projectId);

        Gate::authorize('view', $project);

        $simulation = Simulation::with(['project', 'target', 'simulationType'])->findOrFail($simulationId);

        Gate::authorize('view', $simulation);

        return [
            $simulation,
            $project,
        ];
    }
}
