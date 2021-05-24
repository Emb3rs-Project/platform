<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\StoresSimulations;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class StoreSimulation implements StoresSimulations
{
    /**
     * Validate and create a new Link.
     *
     * @param  int  $projectId
     * @param  array  $input
     * @return Project
     */
    public function store(int $projectId, array $input)
    {
        Gate::authorize('create', Simulation::class);

        $project = Project::findOrFail($projectId);

        Gate::authorize('view', $project);

        $this->validate($input);

        $simulation = $this->save($projectId, $input);

        return $simulation;
    }

    /**
     * Validate the create Link operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        Validator::make($input, [
            'status' => ['filled', 'in:NEW,IN PREPARATION,READY,ANALYSING,STOPPED,ERROR'],
            'target_data' => ['required'], // TODO: Define this rule
            'target_id' => ['required', 'string', 'exists:targets,id'],
            'simulation_type_id' => ['required', 'string', 'exists:simulation_types,id']
        ])
        ->validate();
    }

    /**
     * Save the Link in the DB.
     *
     * @param  int  $projectId
     * @param  array  $input
     * @return Simulation
     */
    protected function save(int $projectId, array $input)
    {
        $simulation = Simulation::create([
            'status' => $input['status'] ?? 'NEW',
            'targetData' => $input['target_data'],
            'project_id' => $projectId,
            'target_id' => $input['target_id'],
            'simulation_type_id' => $input['simulation_type_id'],
        ]);

        return $simulation;
    }
}
