<?php

namespace App\Contracts\Embers\Simulations;

interface StoresSimulations
{
    /**
     * Validate and create a new Simulation.
     *
     * @param  int  $projectId
     * @param  array  $input
     * @return mixed
     */
    public function store(int $projectId, array $input);
}
