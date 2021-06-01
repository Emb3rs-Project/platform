<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Contracts\Embers\Simulations\CreatesSimulations;
use App\Models\Location;
use App\Models\Project;
use App\Models\SimulationType;

class CreateSimulation implements CreatesSimulations
{
    /**
     * Display the necessary objects for the creation of a Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @return mixed
     */
    public function create($user, int $projectId)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'create-simulation'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'show-project'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'index-source'), 401);
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'index-sink'), 401);

        $project = Project::findOrFail($projectId);

        $simulationTypes = SimulationType::all();

        $sources = app(IndexesSources::class)->index($user);

        $sinks = app(IndexesSinks::class)->index($user);

        $locations = Location::all();

        $links = app(IndexesLinks::class)->index($user);

        return [
            $simulationTypes,
            $sources,
            $sinks,
            $links,
            $locations
        ];
    }
}
