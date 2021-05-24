<?php

namespace App\Contracts\Embers\Simulations;

interface CreatesSimulations
{
    /**
     * Display the necessary objects for the creation of a Simulation.
     *
     * @param  int  $projectId
     * @return mixed
     */
    public function create(int $projectId);
}
