<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Integration\ReportsSimulationSteps;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IntegrationStepController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        app(ReportsSimulationSteps::class)->report($request->all());

        return response()->noContent();
    }
}
