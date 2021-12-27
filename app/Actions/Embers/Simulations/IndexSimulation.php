<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\IndexesSimulations;
use App\EmbersPermissionable;
use App\Models\Project;

class IndexSimulation implements IndexesSimulations
{
    use EmbersPermissionable;

    /**
     * Display all the available Simulations.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @return mixed
     */
    public function index($user, int $projectId)
    {
        $this->authorize($user);

        $project = Project::query()->findOrFail($projectId);

        $simulations = $project
            ->simulations()
            ->getQuery()
            ->with(['target', 'simulationType'])
            ->get();

        return $simulations;
    }
}
