<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\ShowsSimulations;
use App\Models\Project;
use App\Models\Simulation;

class ShowSimulation implements ShowsSimulations
{
    /**
     * Find and return an existing Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function show($user, int $projectId, int $simulationId)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-simulation'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-project'), 401);

        $project = Project::with(['location'])->findOrFail($projectId);

        $simulation = Simulation::with(['project', 'target', 'simulationType'])->findOrFail($simulationId);

        return [
            $simulation,
            $project,
        ];
    }
}
