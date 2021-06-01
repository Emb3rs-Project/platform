<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\SharesSimulations;
use App\Models\Project;
use App\Models\Simulation;

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
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'share-simulation'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-project'), 401);

        Project::findOrFail($projectId);

        $simulation = Simulation::with(['project', 'target', 'simulationType'])->findOrFail($simulationId);

        // TODO: generate a sharing link

        return $simulation;
    }
}
