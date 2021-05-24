<?php
namespace App\Contracts\Embers\Simulations;

interface UpdatesSimulations
{
    /**
     * Validate and update an existing Simulation.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @param  array  $input
     * @return mixed
     */
    public function update(int $projectId, int $simulationId, array $input);
}
