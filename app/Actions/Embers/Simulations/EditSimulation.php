<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\EditsSimulations;
use App\Models\Project;
use App\Models\Simulation;

class EditSimulation implements EditsSimulations
{
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
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'edit-simulation'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-project'), 401);

        $project = Project::with(['location'])->findOrFail($projectId);

        $simulation = Simulation::with(['project', 'target', 'simulationType'])->findOrFail($simulationId);

        return [
            $simulation,
            $project,
        ];
    }
}
