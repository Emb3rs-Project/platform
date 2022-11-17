<?php

namespace App\Http\Controllers\Embers;

use App\Actions\Embers\Challenges\ShowChallenge;
use App\Actions\Embers\Challenges\UpdateChallenge;
use App\Contracts\Embers\Challenges\CreatesChallenges;
use App\Contracts\Embers\Challenges\StoresChallenges;
use App\Contracts\Embers\Projects\StoresProjects;
use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeGoal;
use App\Models\ChallengeRestriction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChallengeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
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
     * @return Response
     */
    public function create(Request $request)
    {
        app(CreatesChallenges::class)->create($request->user());
        $challengeGoals = ChallengeGoal::all()->map(fn($goal) => ['value' => $goal->name, 'id' => $goal->id]);
        $challengeRestrictions = ChallengeRestriction::all()->map(fn($restriction) => ['value' => $restriction->name, 'id' => $restriction->id]);
        return Inertia::render('Challenge/ChallengeCreate', [
            'goals' => $challengeGoals,
            'restrictions' => $challengeRestrictions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $goal = $request->input('goal');
        $restrictions = $request->input('restrictions');
        $data = $request->only('name', 'description');
        if ($goal) {
            $data['goal_id'] = $goal['id'];
        }
        $challenge = app(StoresChallenges::class)->store($request->user(), $data);

        foreach ($restrictions as $item) {
            $challenge->restrictions()->attach($item['restriction']['id'], [
                'value' => $item['value']
            ]);
        }

        return redirect()->route('challenges.index');
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $challenge = app(ShowChallenge::class)->show($request->user(), $id);
        return Inertia::render('Challenge/ChallengeShow', [
            'challenge' => $challenge
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $challenge = app(ShowChallenge::class)->show($request->user(), $id);

        $challengeGoals = ChallengeGoal::all()->map(fn($goal) => ['value' => $goal->name, 'id' => $goal->id]);
        $challengeRestrictions = ChallengeRestriction::all()->map(fn($restriction) => ['value' => $restriction->name, 'id' => $restriction->id]);
        return Inertia::render('Challenge/ChallengeEdit', [
            'challenge' => $challenge,
            'goals' => $challengeGoals,
            'restrictions' => $challengeRestrictions
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
        $goal = $request->input('goal');
        $restrictions = $request->input('restrictions');
        $data = $request->only('name', 'description');
        if ($goal) {
            $data['goal_id'] = $goal['id'];
        }
        $challenge = app(UpdateChallenge::class)->update($request->user(), $id, $data);
        $challenge->restrictions()->sync([]);
        foreach ($restrictions as $item) {
            $challenge->restrictions()->attach($item['restriction']['id'], [
                'value' => $item['value']
            ]);
        }

        return redirect()->route('challenges.show', $id);
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
