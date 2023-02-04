<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\News;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $teamInstances = $currentTeam->instances();

        $sources = $teamInstances->whereHas('template', fn($query) => $query->where('category_id', Category::SOURCE))
            ->with(['location', 'template', 'template.category'])
            ->orderBy('created_at', 'desc')->get();

        $sinks = $teamInstances->whereHas('template', fn($query) => $query->where('category_id', Category::SINK))
            ->with(['location', 'template', 'template.category'])
            ->orderBy('created_at', 'desc')->get();

        return Inertia::render('Dashboard', [
            'users' => $users,
            'sources' => cleanCharacterization($sources),
            'sinks' => cleanCharacterization($sinks)
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
