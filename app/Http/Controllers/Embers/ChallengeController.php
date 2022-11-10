<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Challenges\CreatesChallenges;
use App\Contracts\Embers\Challenges\StoresChallenges;
use App\Contracts\Embers\Projects\StoresProjects;
use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {

        $challenges = Challenge::all();
        return Inertia::render('Challenge/ChallengeIndex', [
            'challenges' => $challenges
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        app(CreatesChallenges::class)->create($request->user());
        return Inertia::render('Challenge/ChallengeCreate',[]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        app(StoresChallenges::class)->store($request->user(), $request->all());

        return redirect()->route('challenges.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
