<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\IndexesSimulations;
use App\Models\Project;

class IndexSimulation implements IndexesSimulations
{
    /**
     * Display all the available Simulations.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @return [Simulation]
     */
    public function index($user, int $projectId)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'index-simulation'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-project'), 401);

        $project = Project::findOrFail($projectId);

        $simulations = $project->simulations()->with(['target','simulationType'])->get();

        return $simulations;
    }
}
