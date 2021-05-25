<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Contracts\Embers\Simulations\CreatesSimulations;
use App\Models\Instance;
use App\Models\Link;
use App\Models\Location;
use App\Models\Project;
use App\Models\Simulation;
use App\Models\SimulationType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CreateSimulation implements CreatesSimulations
{
    /**
     * Display the necessary objects for the creation of a Simulation.
     *
     * @param  int  $projectId
     * @return mixed
     */
    public function create(int $projectId)
    {
        Gate::authorize('create', Simulation::class);

        $project = Project::findOrFail($projectId);

        Gate::authorize('view', $project);
        Gate::authorize('viewAny', Instance::class);
        Gate::authorize('viewAny', Link::class);

        $simulationTypes = SimulationType::all();

        $sources = app(IndexesSources::class)->index(Auth::user());

        $sinks = app(IndexesSinks::class)->index(Auth::user());

        $locations = Location::all();

        $links = app(IndexesLinks::class)->index(Auth::user());

        return [
            $simulationTypes,
            $sources,
            $sinks,
            $links,
            $locations
        ];
    }
}
