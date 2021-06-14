<?php

namespace App\Actions\Embers\Simulations;

use App\Contracts\Embers\Simulations\UpdatesSimulations;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\Simulation;
use Illuminate\Support\Facades\Validator;

class UpdateSimulation implements UpdatesSimulations
{
    use EmbersPermissionable;

    /**
     * Validate and create a new Link.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @param  array  $input
     * @return Project
     */
    public function update($user, int $projectId, int $simulationId, array $input)
    {
        $this->authorize($user);

        Project::findOrFail($projectId);

        $simulation = Simulation::findOrFail($simulationId);

        $this->validate($input);

        $simulation = $this->save($simulation, $input);

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
            'target_data' => ['filled'], // TODO: Define this rule
            'target_id' => ['filled', 'string', 'exists:targets,id'],
            'simulation_type_id' => ['filled', 'string', 'exists:simulation_types,id']
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
    protected function save(Simulation $simulation, array $input)
    {
        if (!empty($input['status'])) {
            $simulation->status = $input['status'];
        }

        if (!empty($input['target_data'])) {
            $simulation->targetData = $input['target_data'];
        }

        if (!empty($input['target_id'])) {
            $simulation->target_id = $input['target_id'];
        }

        if (!empty($input['simulation_type_id'])) {
            $simulation->simulation_type_id = $input['simulation_type_id'];
        }

        $simulation->save();

        return $simulation;
    }
}
