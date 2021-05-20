<?php

namespace App\Contracts\Embers\Simulations;

interface EditsSimulations
{
    /**
     * Display the necessary objects for updating a given Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     * @return mixed
     */
    public function edit(mixed $user, int $projectId, int $simulationId);
}
