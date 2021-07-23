<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Simulations\SharesSimulations;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareProjectSimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, int $projectId, int $simulationId)
    {
        $simulation = app(SharesSimulations::class)->share($request->user(), $projectId, $simulationId);

        // return [
        //     "slideOver" => 'Objects/Sinks/SinkShare',
        //     "props" => [
        //         "instance" => $sink
        //     ]
        // ];

        return response()->json([
            "simulation" => $simulation
        ]);
    }
}
