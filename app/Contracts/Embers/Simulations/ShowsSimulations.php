<?php

namespace App\Contracts\Embers\Simulations;

interface ShowsSimulations
{
    /**
     * Display the given Simulations.
     *
     * @param  mixed  $user
     * @param  int    $id
     */
    public function show(mixed $user, int $id);
}
