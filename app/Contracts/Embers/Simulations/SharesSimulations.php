<?php

namespace App\Contracts\Embers\Simulations;

interface SharesSimulations
{
    /**
     * Share a given Simulation.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function share(int $projectId, int $simulationId);
}
