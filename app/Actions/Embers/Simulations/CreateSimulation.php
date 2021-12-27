<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Contracts\Embers\Simulations\CreatesSimulations;
use App\EmbersPermissionable;
use App\Models\Location;
use App\Models\SimulationType;

class CreateSimulation implements CreatesSimulations
{
    use EmbersPermissionable;
    /**
     * Display the necessary objects for the creation of a Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @return mixed
     */
    public function create($user, int $projectId)
    {
        $this->authorize($user);

        $simulationTypes = SimulationType::query()->get();

        $sources = app(IndexesSources::class)->index($user);

        $sinks = app(IndexesSinks::class)->index($user);

        $locations = Location::query()->get();

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
