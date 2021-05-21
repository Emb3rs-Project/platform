<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\ShowsSimulations;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;

class ShowSimulation implements ShowsSimulations
{
    /**
     * Find and return an existing Simulation.
     *
     * @param mixed  $user
     * @param int  $projectId
     * @param int  $simulationId
     * @return mixed
     */
    public function show(mixed $user, int $projectId, int $simulationId)
    {
        $project = Project::with(['location'])->findOrFail($projectId);

        Gate::authorize('view', $project);

        $simulation = Simulation::with(['project', 'target', 'simulationType'])
            ->findOrFail($simulationId);

        Gate::authorize('view', $simulation);

        return [
            $simulation,
            $project,
        ];
    }
}
