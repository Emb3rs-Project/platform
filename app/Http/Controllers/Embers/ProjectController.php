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
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $projects = app(IndexesProjects::class)->index($request->user());

        return Inertia::render('Projects/ProjectIndex', ['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        app(CreatesProjects::class)->create($request->user());

        return Inertia::render('Projects/ProjectCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        app(StoresProjects::class)->store($request->user(), $request->all());

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array<string, mixed>
     */
    public function show(Request $request, $id)
    {
        $project = app(ShowsProjects::class)->show($request->user(), $id);

        return Inertia::render('Projects/ProjectDetails', ['project' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array<string, mixed>
     */
    public function edit(Request $request, $id)
    {
        [
            $project,
            $locations,
        ] = app(EditsProjects::class)->edit($request->user(), $id);

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $updatedProject = app(UpdatesProjects::class)->update($request->user(), $id, $request->all());

        return redirect()->route('projects.show', $updatedProject->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        app(DestroysProjects::class)->destroy($request->user(), $id);

        return redirect()->route('projects.index');
    }
}
