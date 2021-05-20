<?php

namespace App\Contracts\Embers\Simulations;

interface CreatesSimulations
{
    /**
     * Display the necessary objects for the creation of a Simulation.
     *
     * @return mixed
     */
    public function create();
}
