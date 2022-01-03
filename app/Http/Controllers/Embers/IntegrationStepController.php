<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Simulation;
use App\Models\SimulationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

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
        $validated = $request->validate([
            'simulation_uuid' => ['required', Rule::exists(SimulationSession::class, 'simulation_uuid')],
            'simulation_metadata' => ['required', 'array'],
            'simulation_metadata.simulation_step_id' => ['required', 'numeric', 'integer'],
            'simulation_metadata.simulation_step_uuid' => ['required', 'uuid'],
            'simulation_metadata.initial_data' => ['required', 'array'],
            'simulation_metadata.simulation_metadata' => ['required', 'array'],
            'simulation_metadata.simulation_metadata.type' => ['required', Rule::in(['simulation', 'characterization'])],
            'simulation_metadata.has_errors' => ['required', 'boolean'],
            'simulation_step' => ['required', 'array'],
            'simulation_step.simulation_step_id' => ['required', 'numeric', 'integer'],
            'simulation_step.simulation_step_uuid' => ['required', 'uuid'],
            'simulation_step.function_name' => ['required', 'string', 'max:255'],
            'simulation_step.input_data' => ['present', 'array'],
            'simulation_step.output_data' => ['present', 'array'],
            'simulation_step.errors' => ['present', 'array'],
        ]);

        $simulationId = SimulationSession::whereSimulationUuid(Arr::get($validated, 'simulation_uuid'))->pluck('simulation_id');

        $simulation = Simulation::query()->find($simulationId)->first();

        $simulation->integrationReports()->create([
            'data' => Arr::get($validated, 'simulation_step.input_data'),
            'type' => Arr::get($validated, 'simulation_metadata.simulation_metadata.type'),
            'errors' => Arr::get($validated, 'simulation_step.errors'),
            'step_uuid' => Arr::get($validated, 'simulation_step.simulation_step_uuid'),
        ]);

        return response()->noContent();
    }
}
