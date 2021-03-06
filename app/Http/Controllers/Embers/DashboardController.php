<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\News;
use App\Models\Template;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $currentTeam = $request->user()->currentTeam;
        $users = $currentTeam->allUsers();
        $teamInstances = $currentTeam->instances()->get()->pluck('id');

        $sourceCategories = Category::whereType('source')
            ->get('id');
        $templates = Template::whereIn('category_id', $sourceCategories)
            ->get('id');
        $sources = Instance::whereIn('template_id', $templates)
            ->whereIn('id', $teamInstances)
            ->with(['location', 'template', 'template.category'])
            ->get();

        $sinkCategories = Category::whereType('sink')
            ->get('id');
        $templates = Template::whereIn('category_id', $sinkCategories)
            ->get('id');
        $sinks = Instance::whereIn('template_id', $templates)
            ->whereIn('id', $teamInstances)
            ->with(['location', 'template', 'template.category'])
            ->get();

        return Inertia::render('Dashboard', [
            'users' => $users,
            'sources' => $sources,
            'sinks' => $sinks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}
