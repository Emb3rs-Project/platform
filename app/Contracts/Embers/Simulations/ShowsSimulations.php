<?php

namespace App\Contracts\Embers\Simulations;

interface ShowsSimulations
{
    /**
     * Display the given Simulations.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @param  int  $simulationId
     */
    public function show($user, int $projectId, int $simulationId);
}
