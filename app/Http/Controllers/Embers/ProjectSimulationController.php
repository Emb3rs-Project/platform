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
use App\Http\Requests\SimulationRequest;
use App\Models\Instance;
use App\Models\Link;
use App\Models\Project;
use App\Models\Simulation;
use App\Models\SimulationMetadata;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class ProjectSimulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @return \Inertia\Response
     */
    public function index(Request $request, int $projectId)
    {
        $simulations = app(IndexesSimulations::class)->index($request->user(), $projectId);

        return Inertia::render('Simulations/SimulationIndex', [
            'simulations' => $simulations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @return \Inertia\Response
     */
    public function create(Request $request, Project $project)
    {
        // [
        //     $simulationTypes,
        //     $sources,
        //     $sinks,
        //     $links,
        //     $locations
        // ] = app(CreatesSimulations::class)->create($request->user(), $projectId);

        $instances = Auth::user()
            ->currentTeam
            ->instances()
            ->with('location', 'template', 'template.category')
            ->get();

        $links = $request->user()->currentTeam->links()->with([
            'geoSegments'
        ])->get();

        $simulation_metadata = SimulationMetadata::all();

        return Inertia::render('Simulations/SimulationCreate', [
            'instances' => cleanCharacterization($instances, true),
            'links' => $links,
            'project' => $project,
            'simulation_metadata' => $simulation_metadata
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SimulationRequest $request, Project $project)
    {


        $extras = $request->except(['extra.links'])['extra'];
        $simulationMetadata = $request->get('simulation_metadata');
        $simulationType = $simulationMetadata['data']['type'];

        //If we have a standalone simulation all input will come from the json file
        if ($simulationType === 'standalone') {
            $extras['input_data'] = json_decode(file_get_contents($extras['file']), true);
            $extras['sinks'] = [];
            $extras['sources'] = [];
        } else {
            $extras['sinks'] = Instance::with('location', 'template', 'template.category')
                ->whereIn('id', collect($extras['sinks'])->pluck('id'))->get()->toArray();
            $extras['sources'] = Instance::with('location', 'template', 'template.category')
                ->whereIn('id', collect($extras['sources'])->pluck('id'))->get()->toArray();
        }

        $solvers = ['solver_teo', 'solver_gis', 'solver_market'];
        foreach ($solvers as $solver) {
            if (is_array($extras[$solver])) {
                $extras[$solver] = $extras[$solver]['id'];
            }

        }

        $simulation = $project->simulations()->create([
            "status" => "NEW",
            "name" => $request->get('name'),
            "simulation_metadata_id" => $simulationMetadata["id"],
            "extra" => $extras
        ]);

        return redirect()->route('projects.simulations.show', [
            $project->id,
            $simulation->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @param int $simulationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $projectId, int $simulationId)
    {
        [
            $simulation,
            $project
        ] = app(ShowsSimulations::class)->show($request->user(), $projectId, $simulationId);

        return Inertia::render('Simulations/SimulationShow', ["project" => $project, "simulation" => $simulation]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @param int $simulationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $projectId, int $simulationId)
    {

        $project = Project::find($projectId);
        $instances = Auth::user()
            ->currentTeam
            ->instances()
            ->with('location', 'template', 'template.category')
            ->get();
        $simulation = Simulation::find($simulationId);
        $links = $request->user()->currentTeam->links->with([
            'geoSegments'
        ])->get();

        $simulation_metadata = SimulationMetadata::all();

        return Inertia::render('Simulations/SimulationCreate', [
            'instances' => cleanCharacterization($instances) ?? [],
            'links' => $links,
            'project' => $project,
            'simulation_metadata' => $simulation_metadata,
            'mode' => 'update',
            'simulationInputs' => $simulation,
            'simulationMetadataId' => $simulation->simulation_metadata_id,
            'simulationId' => $simulationId
        ]);

        return Inertia::render('Simulations/SimulationCreate', ["project" => $project, "simulation" => $simulation]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @param int $simulationId
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
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @param int $simulationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $projectId, int $simulationId)
    {
        app(DestroysSimulations::class)->destroy($request->user(), $projectId, $simulationId);

        return redirect()->route('my-simulations.index', ['page' => $request->get('page')]);
    }
}
