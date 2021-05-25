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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProjectSimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function index(int $projectId)
    {
        $simulations = app(IndexesSimulations::class)->index($projectId);

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
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function create(int $projectId)
    {
        [
            $simulationTypes,
            $sources,
            $sinks,
            $links,
            $locations
        ] = app(CreatesSimulations::class)->create($projectId);

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
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $projectId)
    {
        app(StoresSimulations::class)->store($projectId, $request->all());

        return Redirect::route('projects.simulations.index', $projectId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\Response
     */
    public function show(int $projectId, int $simulationId)
    {
        [
            $simulation,
            $project
        ] = app(ShowsSimulations::class)->show($projectId, $simulationId);

        return response()->json([
            'simulation' => $simulation,
            'project' => $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\Response
     */
    public function edit(int $projectId, int $simulationId)
    {
        [
            $simulation,
            $project
        ] = app(EditsSimulations::class)->edit($projectId, $simulationId);

        return response()->json([
            'simulation' => $simulation,
            'project' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $projectId, int $simulationId)
    {
        $updatedSimulation = app(UpdatesSimulations::class)->update($projectId, $simulationId, $request->all());

        return Redirect::route('projects.simulations.show', [
            $projectId,
            $updatedSimulation->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $projectId, int $simulationId)
    {
        app(DestroysSimulations::class)->destroy($projectId, $simulationId);

        return Redirect::route('projects.simulations.index', $projectId);
    }
}
