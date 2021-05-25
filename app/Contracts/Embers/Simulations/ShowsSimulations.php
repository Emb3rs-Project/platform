<?php

namespace App\Contracts\Embers\Simulations;

interface ShowsSimulations
{
    /**
     * Display the given Simulations.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     */
    public function show(int $projectId, int $simulationId);
}
