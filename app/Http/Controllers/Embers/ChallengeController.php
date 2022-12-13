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
use App\Models\Instance;
use App\Models\IntegrationReport;
use App\Models\Link;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $data['goal_id'] = $goal['id'] ?? null;
        }
        $challenge = app(StoresChallenges::class)->store($request->user(), $data);

        foreach ($restrictions as $item) {
            if (array_key_exists('id', $item['restriction'])) {
                $challenge->restrictions()->attach($item['restriction']['id'], [
                    'value' => $item['value']
                ]);
            }
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
        $instances_id = Auth::user()->currentTeam->instances->pluck("id");
        $instances = Instance::with('location', 'template', 'template.category')->whereIn('id', $instances_id)->get();

        $isEnrolled = $challenge->whereHas('participants', function ($query) use ($id){
            return $query->where('user_id', Auth::id())->where('challenge_id', $id);
        })->get();

        $teamLinks = $request->user()->currentTeam->links->pluck('id');

        $participants = [];

        $challenge->participants->each(function ($participant) use (&$participants, $challenge) {
            if ($participant->pivot->sessions->count() > 0) {
                $participant->pivot->sessions->each(function ($session) use ($participant, &$participants, $challenge) {
                    $reports = IntegrationReport::where(
                        'simulation_uuid',
                        'like',
                        $session->simulation_uuid
                    )
                        ->orderBy('created_at')
                        ->get();
                    $arr = [];
                    $reports->each(function ($report) use (&$arr) {
                        $arr = array_merge($arr, json_decode($report->output, true));
                    });
                    $dot = new \Adbar\Dot($arr);
                    $customData = [];
                    $customData['goal_value'] = 0;
                    $customData['goal_unit'] = $challenge->goal->unit;
                    $customData['session_id'] = $session->id;
                    $customData['session_url'] = route('session.show', $session->id);
                    if (!empty($challenge->goal->output)) {
                        $customData['goal_value'] = $dot->get($challenge->goal->output, 0);
                    }
                    $participants[] = array_merge($participant->toArray(), $customData);
                });
            } else {
                $participant['goal_value'] = 0;
                $participants[] = $participant;
            }
        });
        $links = Link::with([
            'geoSegments'
        ])->whereIn('id', $teamLinks)->get();
        return Inertia::render('Challenge/ChallengeShow', [
            'challenge' => $challenge,
            'participants' => $participants,
            'instances' => $instances,
            'links' => $links,
            'isEnrolled' => $isEnrolled->count() > 0
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

    public function enroll(Request $request)
    {
        $challenge = Challenge::find($request->input('challenge'));
        if ($challenge) {
            $challenge->participants()->attach($request->input('user'));
            return [
                'error' => false
            ];
        }

        return [
            'error' => true
        ];
    }

    public function submit(Request $request)
    {
        $session = SimulationSession::find($request->input('session'));

        if ($session) {
            $session->challenge()->attach($request->input('challenge'), [
                'challenge_user_id' => $request->input('challenge_user')
            ]);

            return [
                'error' => false
            ];
        }
        return [
            'error' => true
        ];
    }
}
