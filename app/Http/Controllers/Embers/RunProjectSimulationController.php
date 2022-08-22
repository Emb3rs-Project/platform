<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Integration\StartsSimulations;
use App\Contracts\Embers\Simulations\SharesSimulations;
use App\Http\Controllers\Controller;
use App\Jobs\StartSimulation;
use App\Models\Project;
use App\Models\Simulation;
use App\Nova\Actions\RunSimulationSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Laravel\Nova\Actions\ActionMethod;
use Laravel\Nova\Actions\CallQueuedAction;
use Laravel\Nova\Fields\ActionFields;
use Queue;
use Ramsey\Uuid\Uuid;

class RunProjectSimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @param int $simulationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, Project $project, Simulation $simulation)
    {
        $uuid = Uuid::uuid4();

        $newSession = $simulation->simulationSessions()->create([
            "simulation_uuid" => $uuid
        ]);

        $simulation->load('simulationMetadata');
        $simulation->status = "IN PREPARATION";
        $simulation->save();


        StartSimulation::dispatch($newSession);

        // $action = new RunSimulationSession();
        // $models = Collection::wrap($newSession);
        // $method = ActionMethod::determine($action, $models->first());

        // Queue::connection($action->connection)->pushOn(
        //     $action->queue,
        //     new CallQueuedAction(
        //         $action,
        //         $method,
        //         new ActionFields(new \Illuminate\Support\Collection(), new Collection()),
        //         $models,
        //         0
        //     )
        // );

        if ($request->input('onRow')) {
            return redirect()->route('my-simulations.index', ['page' => $request->query('page')])->with('success', 'Simulation is Running');
        }


        return redirect()->route('projects.simulations.show', ["project" => $project->id, "simulation" => $simulation->id]);
    }
}
