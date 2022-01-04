<?php

namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\ReportsCharacterizationFinishes;
use App\Models\Simulation;
use App\Models\SimulationSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportCharacterizationFinish implements ReportsCharacterizationFinishes
{
    public function report(array $input): void
    {
        $validated = Validator::make($input, [
            'simulation_uuid' => ['required', Rule::exists(SimulationSession::class, 'simulation_uuid')],
            'simulation_metadata' => ['required', 'array'],
            'simulation_metadata.simulation_step_id' => ['required', 'numeric', 'integer'],
            'simulation_metadata.simulation_step_uuid' => ['required', 'uuid'],
            'simulation_metadata.initial_data' => ['required', 'array'],
            'simulation_metadata.simulation_metadata' => ['required', 'array'],
            'simulation_metadata.simulation_metadata.type' => ['required', Rule::in(['simulation', 'characterization'])],
            'simulation_metadata.has_errors' => ['required', 'boolean'],
        ])->validate();

        $simulationId = SimulationSession::whereSimulationUuid(Arr::get($validated, 'simulation_uuid'))->pluck('simulation_id');

        $simulation = Simulation::query()->find($simulationId)->first();

        $simulation->simulationResults()->create([
            'data' => Arr::get($validated, 'simulation_metadata')
        ]);
    }
}
