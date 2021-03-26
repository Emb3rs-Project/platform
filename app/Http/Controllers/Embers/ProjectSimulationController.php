<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Link;
use App\Models\Location;
use App\Models\Project;
use App\Models\Simulation;
use App\Models\SimulationType;
use App\Models\Template;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectSimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        $simulations = $project->simulations()->with(['target','simulationType'])->get();

        dd($project);

        return Inertia::render('Simulations/SimulationIndex', [
            'simulations' => $simulations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamInstances = Auth::user()->currentTeam->instances->pluck('id');

        // Get Locations
        $locations = Location::all();

        // Get Sinks
        $sourceCategories = Category::whereType('sink')
        ->get()
        ->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
        ->get()
        ->pluck('id');

        $sinks = Instance::whereIn('template_id', $sourceTemplates)
        ->whereIn('id', $teamInstances)
        ->with(['template', 'template.category', 'location.geoObject'])
        ->get();

        // Get Sources
        $sourceCategories = Category::whereType('source')
        ->get()
        ->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
        ->get()
        ->pluck('id');

        $sources = Instance::whereIn('template_id', $sourceTemplates)
        ->whereIn('id', $teamInstances)
        ->with(['template', 'template.category', 'location.geoObject'])
        ->get();

        // Get Simulation Types
        $simulationTypes = SimulationType::all();

        $teamLinks = Auth::user()->currentTeam->links->pluck('id');
        $links = Link::whereIn('id', $teamLinks)->get();


        return Inertia::render('Simulations/SimulationCreate', [
            'sources' => $sources,
            'sinks' => $sinks,
            'locations' => $locations,
            'simulation_types' => $simulationTypes,
            'links' => $links
        ]);
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
