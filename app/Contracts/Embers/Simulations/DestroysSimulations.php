<?php

namespace App\Contracts\Embers\Simulations;

interface DestroysSimulations
{
    /**
     * Delete an existing Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function destroy($user, int $projectId, int $simulationId);
}
