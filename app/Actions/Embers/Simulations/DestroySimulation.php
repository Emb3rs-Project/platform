<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\DestroysSimulations;
use App\Models\Project;
use App\Models\Simulation;

class DestroySimulation implements DestroysSimulations
{
    /**
     * Find and delete an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @param  array  $input
     * @return void
     */
    public function destroy($user, int $projectId, int $simulationId)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'destroy-simulation'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-project'), 401);

        Project::findOrFail($projectId);

        $simulation = Simulation::findOrFail($simulationId);

        Simulation::destroy($simulation->id);
    }
}
