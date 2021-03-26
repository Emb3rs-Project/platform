<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Project;
use App\Models\Simulation;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teamProjects = Auth::user()->currentTeam->projects->pluck('id');
        $projects = Project::whereIn('id', $teamProjects)
            ->with(['location'])
            ->get();

        $output = $projects->map(function ($item) {
            if (isset($item->location)) {
                $item['data'] = $item->location->geoObject;
            }

            return $item;
        });

        return Inertia::render('Projects/ProjectIndex', [
            'projects' => $output
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = Location::with(['geoObject'])->get();

        return Inertia::render('Projects/ProjectCreate', [
            'locations' => $locations
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
        $newProject = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'location_id' => $request->get('location_id')
        ];

        $project = Project::create($newProject);
        $project->teams()->attach(Auth::user()->currentTeam);

        return Redirect::route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::whereId($id)->with(['location', 'location.geoObject'])->first();

        $projectId = $project->id;

        $simulations = Simulation::whereProjectId($projectId)->with(['simulationType', 'simulationType.unit'])->get();

        return Inertia::render('Projects/ProjectDetails', [
            'project' => $project,
            'simulations' => $simulations
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::whereId($id)->with(['location'])->first();

        $locations = Location::all();

        return Inertia::render('Projects/ProjectEdit', [
            'project' => $project,
            'locations' => $locations
        ]);
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
        Project::findOrFail($id)->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'location_id' => $request->get('location_id')
        ]);

        return Redirect::route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Project::destroy($id);
        return Redirect::route('projects.index');
    }
}
