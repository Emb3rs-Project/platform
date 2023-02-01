<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Contracts\Embers\Simulations\SharesSimulations;
use App\Events\Embers\SimulationRunning;
use App\Events\Embers\SimulationUpdate;
use App\Http\Controllers\Controller;
use App\Jobs\StartSimulation;
use App\Models\Project;
use App\Models\Simulation;
use App\Notifications\Embers\ImportNotification;
use App\Nova\Actions\RunSimulationSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Laravel\Nova\Actions\ActionMethod;
use Laravel\Nova\Actions\CallQueuedAction;
use Laravel\Nova\Fields\ActionFields;
use Queue;
use Ramsey\Uuid\Uuid;

class UpdateProjectSimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @param int $simulationId
     */
    public function __invoke(Request $request, Project $project, Simulation $simulation)
    {

        $simulationUid = $request->input('simulation_uuid');
        $newSession = $simulation->simulationSessions()
            ->where('simulation_uuid', $simulationUid)
            ->first();

        $simulation->load('simulationMetadata');
        $simulation->status = Simulation::RUNNING;
        $simulation->requested_by = $request->user()?->id;
        $simulation->save();

        $simulationData = json_decode(Redis::get($simulationUid),true) ?? [];
        $simulationData['river_data']['buildmodel_platform_input']['platform_technologies'] = $request->input('technologies');
        $payload = $simulationData;


        StartSimulation::dispatch($newSession, true, $payload);

        broadcast(new SimulationRunning($simulation->id));
        if ($request->input('onRow')) {

            broadcast(new SimulationUpdate($simulation->id));

            /* $request->user()->notify(new ImportNotification($request->user(), $request->user()->currentTeam,
                 [],
                 'Simulation is Running. You will be notified when the simulation is finished'
             ));*/
            return response()->json([]);
        }


        return redirect()->route('projects.simulations.show', ["project" => $project->id, "simulation" => $simulation->id]);
    }
}
