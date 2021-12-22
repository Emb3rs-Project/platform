<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\IntegrationReport;
use App\Models\Simulation;
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
            // 'simulation_id' => ['required', 'numeric', 'integer', Rule::exists(Simulation::class, 'id')],
            'simulation_id' => ['required', 'numeric', 'integer'],
            'report' => ['required', 'array'],
            'report.simulation_metadata' => ['required', 'array'],
            'report.simulation_metadata.simulation_step_id' => ['required', 'numeric', 'integer'],
            'report.simulation_metadata.simulation_step_uuid' => ['required', 'uuid'],
            'report.simulation_metadata.initial_data' => ['required', 'array'],
            'report.simulation_metadata.simulation_metadata' => ['required', 'array'],
            'report.simulation_metadata.simulation_metadata.type' => ['required', Rule::in(['simulation', 'characterization'])],
            'report.simulation_metadata.has_errors' => ['required', 'boolean'],
            'report.data' => ['required', 'array'],
            'report.data.simulation_step_id' => ['required', 'numeric', 'integer'],
            'report.data.simulation_step_uuid' => ['required', 'uuid'],
            'report.data.function_name' => ['required', 'string', 'max:255'],
            'report.data.input_data' => ['present', 'array'],
            'report.data.output_data' => ['present', 'array'],
            'report.data.errors' => ['present', 'array'],
        ]);

        // info($validated);

        IntegrationReport::query()->create([
            'data' => Arr::get($validated, 'report.data.output_data'),
            'type' => Arr::get($validated, 'report.simulation_metadata.simulation_metadata'),
            'errors' => Arr::get($validated, 'report.data.errors'),
            'step_uuid' => Arr::get($validated, 'report.data.simulation_step_uuid'),
        ]);

        // return response()->json([$validated]);
    }
}
