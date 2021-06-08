<?php

namespace App\Contracts\Embers\Simulations;

interface StoresSimulations
{
    /**
     * Validate and create a new Simulation.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  array  $input
     * @return mixed
     */
    public function store($user, int $projectId, array $input);
}
