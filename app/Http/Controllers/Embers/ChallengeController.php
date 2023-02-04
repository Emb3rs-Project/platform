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
use App\Models\Project;
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
        $user = $request->user();
        app(CreatesChallenges::class)->create($request->user());
        $challengeGoals = ChallengeGoal::all()->map(fn($goal) => ['value' => $goal->name . ($goal->unit ? '(' . $goal->unit . ')' : ''), 'id' => $goal->id]);
        $challengeRestrictions = ChallengeRestriction::all()->map(fn($restriction) => ['value' => $restriction->name . ($restriction->unit ? '(' . $restriction->unit . ')' : ''), 'id' => $restriction->id]);
        return Inertia::render('Challenge/ChallengeCreate', [
            'goals' => $challengeGoals,
            'restrictions' => $challengeRestrictions,
            'projects' => $user->currentTeam->projects?->map(fn($item) => [
                'key' => $item->id,
                'value' => $item->name,
            ])
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
        $user = $request->user();
        $goal = $request->input('goal');
        $project = $request->input('project');
        $restrictions = $request->input('restrictions');
        $data = $request->only('name', 'description');
        $data['created_by'] = $user->id;
        if ($goal) {
            $data['goal_id'] = $goal['id'] ?? null;
        }
        if ($project) {
            $projectModel = Project::find($project['key']);
            if ($projectModel) {
                $cloneProject = $projectModel->replicate();
                $cloneProject->name = 'Challenge ' . $data['name'] . ' - ' . $projectModel->name;
                $cloneProject->push();
                $cloneProject->teams()->attach($user->currentTeam);
                $data['project_id'] = $cloneProject->id;
            }
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
        $user = $request->user();
        $challenge = app(ShowChallenge::class)->show($request->user(), $id);
        $instances = Auth::user()
            ->currentTeam
            ->instances()
            ->with('location', 'template', 'template.category')
            ->get();

        $isEnrolled = $challenge->whereHas('participants', function ($query) use ($id) {
            return $query->where('user_id', Auth::id())->where('challenge_id', $id);
        })->get();

        $links = $request->user()->currentTeam->links()->with([
            'geoSegments'
        ])->get();

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
                    $customData['hasRestriction'] = false;
                    $customData['restrictions'] = [];
                    $challenge->restrictions->each(function ($restriction) use (&$customData, $challenge, $dot) {
                        if ($restriction->output && $dot->get($restriction->output, 0) >= $restriction->pivot->value) {
                            $customData['restrictions'][] = $restriction->name . ':  ' . $dot->get($restriction->output, 0) . '(' . $restriction->unit . ') > ' . $restriction->pivot->value . '(' . $restriction->unit . ')';
                            $customData['hasRestriction'] = true;
                        }
                    });
                    $participants[] = array_merge($participant->toArray(), $customData);
                });
            } else {
                $participant['goal_value'] = '';
                $participants[] = $participant;
            }
        });
        return Inertia::render('Challenge/ChallengeShow', [
            'challenge' => $challenge,
            'participants' => $participants,
            'instances' => cleanCharacterization($instances),
            'links' => $links,
            'isEnrolled' => $isEnrolled->count() > 0,
            'projects' => $user->currentTeam->projects?->map(fn($item) => [
                'key' => $item->id,
                'value' => $item->name,
            ])
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
        $user = $request->user();

        $challengeGoals = ChallengeGoal::all()->map(fn($goal) => ['value' => $goal->name, 'id' => $goal->id]);
        $challengeRestrictions = ChallengeRestriction::all()->map(fn($restriction) => ['value' => $restriction->name, 'id' => $restriction->id]);
        return Inertia::render('Challenge/ChallengeEdit', [
            'challenge' => $challenge,
            'goals' => $challengeGoals,
            'restrictions' => $challengeRestrictions,
            'projects' => $user->currentTeam->projects?->map(fn($item) => [
                'key' => $item->id,
                'value' => $item->name,
            ])
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
        $user = $request->user();
        $challenge = Challenge::find($request->input('challenge'));
        if ($challenge) {
            $project = $request->input('project');
            if ($project) {
                $projectModel = Project::find($project['key']);
                if ($projectModel) {
                    $cloneProject = $projectModel->replicate();
                    $cloneProject->name = 'Challenge ' . $challenge->name . ' - ' . $projectModel->name;
                    $cloneProject->push();
                    $cloneProject->teams()->attach($user->currentTeam);
                    $cloneProject->teams()->attach($challenge->createdBy?->currentTeam);
                }
            }
            if ($challenge->project) {
                $challenge->project->teams()->attach($user->currentTeam);
            }
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
