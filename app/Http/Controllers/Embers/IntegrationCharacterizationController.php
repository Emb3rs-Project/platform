<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Integration\ReportsCharacterizationFinishes;
use App\Http\Controllers\Controller;
use App\Models\Simulation;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class IntegrationCharacterizationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        app(ReportsCharacterizationFinishes::class)->report($request->all());

        return response()->noContent();
    }
}
