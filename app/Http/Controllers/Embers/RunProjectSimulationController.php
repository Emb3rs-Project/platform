<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Simulations\SharesSimulations;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Ramsey\Uuid\Uuid;

class RunProjectSimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $simulationId
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


        $package = [
            "simulation_uuid" => $uuid,
            "simulation_metadata" => $simulation->simulationMetadata->data,
            "initial_data" => $simulation->extra["input_data"]
        ];

        Redis::publish('simulation_started', json_encode($package));


        return  redirect()->route('projects.simulations.show', ["project" => $project->id, "simulation" => $simulation->id]);
    }
}
