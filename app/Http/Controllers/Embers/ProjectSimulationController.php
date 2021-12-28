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
use Inertia\Inertia;

class ProjectSimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Inertia\Response
     */
    public function index(Request $request, int $projectId)
    {
        $simulations = app(IndexesSimulations::class)->index($request->user(), $projectId);

        return Inertia::render('Simulations/SimulationIndex', [
            'simulations' => $simulations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Inertia\Response
     */
    public function create(Request $request, int $projectId)
    {
        [
            $simulationTypes,
            $sources,
            $sinks,
            $links,
            $locations
        ] = app(CreatesSimulations::class)->create($request->user(), $projectId);

        return Inertia::render('Simulations/SimulationCreate', [
            'simulationTypes' => $simulationTypes,
            'sources' => $sources,
            'sinks' => $sinks,
            'links' => $links,
            'locations' => $locations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, int $projectId)
    {
        app(StoresSimulations::class)->store($request->user(), $projectId, $request->all());

        return redirect()->route('projects.simulations.index', $projectId);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $projectId, int $simulationId)
    {
        [
            $simulation,
            $project
        ] = app(ShowsSimulations::class)->show($request->user(), $projectId, $simulationId);

        return response()->json([
            'simulation' => $simulation,
            'project' => $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $projectId, int $simulationId)
    {
        [
            $simulation,
            $project
        ] = app(EditsSimulations::class)->edit($request->user(), $projectId, $simulationId);

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $projectId, int $simulationId)
    {
        $updatedSimulation = app(UpdatesSimulations::class)->update(
            $request->user(),
            $projectId,
            $simulationId,
            $request->all()
        );

        return redirect()->route('projects.simulations.show', [
            $projectId,
            $updatedSimulation->id
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $projectId, int $simulationId)
    {
        app(DestroysSimulations::class)->destroy($request->user(), $projectId, $simulationId);

        return redirect()->route('projects.simulations.index', $projectId);
    }
}
