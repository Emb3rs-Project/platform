<?php

namespace App\Actions\Embers\Integration;

use App\Contracts\Embers\Integration\ReportsSimulationSteps;
use App\Models\Simulation;
use App\Models\SimulationSession;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReportSimulationStep implements ReportsSimulationSteps
{
    public function report(array $validated): void
    {
        $simulationId = SimulationSession::whereSimulationUuid(Arr::get($validated, 'simulation_uuid'))->pluck('simulation_id');

        $simulation = Simulation::query()->find($simulationId)->first();

        $simulation->integrationReports()->create([
            'data' => Arr::get($validated, 'simulation_step.input_data'),
            'type' => Arr::get($validated, 'simulation_metadata.simulation_metadata.type'),
            'errors' => Arr::get($validated, 'simulation_step.errors'),
            'step_uuid' => Arr::get($validated, 'simulation_step.simulation_step_uuid'),
        ]);
    }
}
