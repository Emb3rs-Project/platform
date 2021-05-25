<?php

namespace App\Contracts\Embers\Simulations;

interface EditsSimulations
{
    /**
     * Display the necessary objects for updating a given Simulation.
     *
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function edit(int $projectId, int $simulationId);
}
