<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\IndexesSimulations;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;

class IndexSimulation implements IndexesSimulations
{
    /**
     * Display all the available Simulations.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @return [Simulation]
     */
    public function index(mixed $user, int $projectId)
    {
        Gate::authorize('viewAny', Simulation::class);

        $project = Project::findOrFail($projectId);

        Gate::authorize('view', $project);

        $simulations = $project->simulations()->with(['target','simulationType'])->get();

        return $simulations;
    }
}
