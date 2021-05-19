<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Projects\CreatesProjects;
use App\Contracts\Embers\Projects\DestroysProjects;
use App\Contracts\Embers\Projects\EditsProjects;
use App\Contracts\Embers\Projects\IndexesProjects;
use App\Contracts\Embers\Projects\ShowsProjects;
use App\Contracts\Embers\Projects\StoresProjects;
use App\Contracts\Embers\Projects\UpdatesProjects;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = app(IndexesProjects::class)->index(Auth::user());

        return [
            'slideOver' => 'Projects/ProjectIndex',
            'props' => [
                'projects' => $projects
            ]
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = app(CreatesProjects::class)->create();

        return [
            'slideOver' => 'Projects/ProjectCreate',
            'props' =>[
                'locations' => $locations
            ]
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        app(StoresProjects::class)->store(Auth::user(), $request->all());

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
        [
            $project,
            $simulations
        ] = app(ShowsProjects::class)->show(Auth::user(), $id);

        return [
            'slideOver' => 'Projects/ProjectDetails',
            'props' => [
                'project' => $project,
                'simulations' => $simulations
            ]
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        [
            $project,
            $locations,
        ] = app(EditsProjects::class)->edit(Auth::user(), $id);

        return [
            'slideOver' => 'Projects/ProjectEdit',
            'props' => [
                'project' => $project,
                'locations' => $locations
            ]
        ];
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
        $updatedProject = app(UpdatesProjects::class)->update(Auth::user(), $id, $request->all());

        return Redirect::route('projects.show', $updatedProject->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        app(DestroysProjects::class)->destroy(Auth::user(), $id);

        return Redirect::route('projects.index');
    }
}
