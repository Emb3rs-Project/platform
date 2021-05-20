<?php

namespace App\Contracts\Embers\Simulations;

interface CreatesSimulations
{
    /**
     * Display the necessary objects for the creation of a Simulation.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create(mixed $user);
}
