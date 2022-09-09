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
use App\Models\Instance;
use App\Models\Link;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
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
     * @param \Illuminate\Http\Request $request
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
     * @param \Illuminate\Http\Request $request
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array<string, mixed>
     */
    public function show(Request $request, $id)
    {
        $instances_id = Auth::user()->currentTeam->instances->pluck("id");
        $instances = Instance::with('location', 'template', 'template.category')->whereIn('id', $instances_id)->get();

        $teamLinks = $request->user()->currentTeam->links->pluck('id');

        $links = Link::with([
            'geoSegments'
        ])->whereIn('id', $teamLinks)->get();

        $project = app(ShowsProjects::class)->show($request->user(), $id);

        return Inertia::render('Projects/ProjectDetails', [
            'instances' => $instances,
            'links' => $links,
            'project' => $project,

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
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
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        app(DestroysProjects::class)->destroy($request->user(), $id);

        return redirect()->route('projects.index');
    }


    public function importFile(Request $request)
    {
        $file = $request->file('file');

        $filePath = $file->store('imports');
        list($path, $filename) = explode('/', $filePath);
        $import = "\App\Jobs\Import{$request->input('action')}::dispatch";

        call_user_func($import, $filename, $request->user());

        return redirect()->back()->with('flash', ['success' => 'The file is being processed in the background. You will receive a notification when finished.']);
        //$import::dispatch($filename, request()->user());
    }
}
