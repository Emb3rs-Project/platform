<?php

namespace App\Contracts\Embers\Simulations;

interface DestroysSimulations
{
    /**
     * Delete an existing Simulation.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function destroy(int $projectId, int $simulationId);
}
