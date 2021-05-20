<?php

namespace App\Contracts\Embers\Simulations;

interface StoresSimulations
{
    /**
     * Validate and create a new Simulation.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store(mixed $user, array $input);
}
