<?php
namespace App\Contracts\Embers\Simulations;

interface UpdatesSimulations
{
    /**
     * Validate and update an existing Simulation.
     *
     * @param  mixed  $user
     * @param  int    $projectId
     * @param  int    $simulationId
     * @param  array  $input
     * @return mixed
     */
    public function update($user, int $projectId, int $simulationId, array $input);
}
