<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\DestroysSimulations;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;

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
    public function destroy(mixed $user, int $projectId, int $simulationId)
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('view', $project);

        $simulation = Simulation::findOrFail($simulationId);

        Gate::authorize('delete', $simulation);

        Simulation::destroy($simulation->id);
    }
}
