<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Integration\ReportsSimulationFinishes;
use App\Http\Controllers\Controller;
use App\Models\Simulation;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class IntegrationSimulationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        app(ReportsSimulationFinishes::class)->report($request->all());

        return response()->noContent();
    }
}
