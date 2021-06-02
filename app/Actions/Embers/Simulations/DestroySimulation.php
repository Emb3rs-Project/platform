<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\DestroysSimulations;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\Simulation;

class DestroySimulation implements DestroysSimulations
{
    use EmbersPermissionable;

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
        $this->authorize($user);

        $simulation = Simulation::findOrFail($simulationId);

        Simulation::destroy($simulation->id);
    }
}
