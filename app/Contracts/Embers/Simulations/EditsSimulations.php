<?php

namespace App\Contracts\Embers\Simulations;

interface EditsSimulations
{
    /**
     * Display the necessary objects for updating a given Simulation.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function edit(mixed $user, int $id);
}
