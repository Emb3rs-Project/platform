<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\SharesSimulations;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;

class ShareSimulation implements SharesSimulations
{
    /**
     * Find and return an existing Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function share($user, int $projectId, int $simulationId)
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('view', $project);

        $simulation = Simulation::with(['project', 'target', 'simulationType'])->findOrFail($simulationId);

        Gate::authorize('view', $simulation);
        // TODO: also check for sharing permissions

        // TODO: generate a sharing link

        return $simulation;
    }
}
