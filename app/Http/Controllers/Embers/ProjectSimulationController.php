<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Simulations\CreatesSimulations;
use App\Contracts\Embers\Simulations\IndexesSimulations;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Simulation $simulation)
    {
        dd($project, $simulation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, Simulation $simulation)
    {
        dd($project, $simulation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, Simulation $simulation)
    {
        dd($project, $simulation);
    }
}
