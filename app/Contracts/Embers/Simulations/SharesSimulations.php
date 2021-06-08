<?php

namespace App\Contracts\Embers\Simulations;

interface SharesSimulations
{
    /**
     * Share a given Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function share($user, int $projectId, int $simulationId);
}
