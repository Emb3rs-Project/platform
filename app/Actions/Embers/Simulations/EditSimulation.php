<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\EditsSimulations;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\Simulation;

class EditSimulation implements EditsSimulations
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for updating a given Project.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function edit($user, int $projectId, int $simulationId)
    {
        $this->authorize($user);

        $project = Project::query()->findOrFail($projectId);

        $simulation = Simulation::query()->with(['project', 'target', 'simulationType'])->findOrFail($simulationId);

        return [
            $simulation,
            $project,
        ];
    }
}
