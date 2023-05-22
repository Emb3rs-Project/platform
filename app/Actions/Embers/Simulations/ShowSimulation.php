<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\ShowsSimulations;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\Simulation;
use App\Models\User;

class ShowSimulation implements ShowsSimulations
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
    public function show($user, int $projectId, int $simulationId)
    {
        $this->authorize($user);

        $project = Project::query()->findOrFail($projectId);

        $simulation = Simulation::query()->with(['project', 'simulationMetadata', 'simulationSessions'])
            ->onInstitutionFor($user)
            ->findOrFail($simulationId);

        $simulation->extra = [];
        return [
            $simulation,
            $project,
        ];
    }
}
