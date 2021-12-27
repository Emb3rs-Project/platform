<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\SharesSimulations;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\Simulation;

class ShareSimulation implements SharesSimulations
{
    use EmbersPermissionable;

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
        $this->authorize($user);

        Project::query()->findOrFail($projectId);

        $simulation = Simulation::query()->with(['project', 'target', 'simulationType'])->findOrFail($simulationId);

        // TODO: generate a sharing link

        return $simulation;
    }
}
