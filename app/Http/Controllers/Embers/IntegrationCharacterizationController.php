<?php

namespace App\Http\Controllers\Embers;

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
        $validated = $request->validate([
            'simulation_uuid' => ['required', Rule::exists(SimulationSession::class, 'simulation_uuid')],
            'simulation_metadata' => ['required', 'array'],
            'simulation_metadata.simulation_step_id' => ['required', 'numeric', 'integer'],
            'simulation_metadata.simulation_step_uuid' => ['required', 'uuid'],
            'simulation_metadata.initial_data' => ['required', 'array'],
            'simulation_metadata.simulation_metadata' => ['required', 'array'],
            'simulation_metadata.simulation_metadata.type' => ['required', Rule::in(['simulation', 'characterization'])],
            'simulation_metadata.has_errors' => ['required', 'boolean'],
        ]);

        $simulationId = SimulationSession::whereSimulationUuid(Arr::get($validated, 'simulation_uuid'))->pluck('simulation_id');

        $simulation = Simulation::query()->find($simulationId)->first();

        $simulation->simulationResults()->create(['data' => Arr::get($validated, 'simulation_metadata')]);

        return response()->noContent();
    }
}
