<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Simulations\CreatesSimulations;
use App\Contracts\Embers\Simulations\DestroysSimulations;
use App\Contracts\Embers\Simulations\EditsSimulations;
use App\Contracts\Embers\Simulations\IndexesSimulations;
use App\Contracts\Embers\Simulations\ShowsSimulations;
use App\Contracts\Embers\Simulations\StoresSimulations;
use App\Contracts\Embers\Simulations\UpdatesSimulations;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProjectSimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $projectId)
    {
        $simulations = app(IndexesSimulations::class)->index(Auth::user(), $projectId);

        return response()->json([
            'simulations' => $simulations
        ]);

        // return Inertia::render('Simulations/SimulationIndex', [
        //     'simulations' => $simulations
        // ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        [
            $simulationTypes,
            $sources,
            $sinks,
            $links,
            $locations
        ] = app(CreatesSimulations::class)->create(Auth::user());

        return response()->json([
            'simulationTypes' => $simulationTypes,
            'sources' => $sources,
            'sinks' => $sinks,
            'links' => $links,
            'locations' => $locations,
        ]);

        // return Inertia::render('Simulations/SimulationCreate', [
        //     'simulationTypes' => $simulationTypes,
        //     'sources' => $sources,
        //     'sinks' => $sinks,
        //     'links' => $links,
        //     'locations' => $locations,
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $projectId)
    {
        app(StoresSimulations::class)->store(Auth::user(), $projectId, $request->all());

        return Redirect::route('projects.simulations.index', $projectId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $projectId, int $simulationId)
    {
        [
            $simulation,
            $project
        ] = app(ShowsSimulations::class)->show(Auth::user(), $projectId, $simulationId);

        return response()->json([
            'simulation' => $simulation,
            'project' => $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $projectId, int $simulationId)
    {
        [
            $simulation,
            $project
        ] = app(EditsSimulations::class)->edit(Auth::user(), $projectId, $simulationId);

        return response()->json([
            'simulation' => $simulation,
            'project' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $projectId, int $simulationId)
    {
        $updatedSimulation = app(UpdatesSimulations::class)
                                ->update(Auth::user(), $projectId, $simulationId, $request->all());

        return Redirect::route('projects.simulations.show', [
            $projectId,
            $updatedSimulation->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $projectId, int $simulationId)
    {
        app(DestroysSimulations::class)->destroy(Auth::user(), $projectId, $simulationId);

        return Redirect::route('projects.simulations.index', $projectId);
    }
}
