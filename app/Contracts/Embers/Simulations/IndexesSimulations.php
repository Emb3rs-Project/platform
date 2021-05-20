<?php

namespace App\Contracts\Embers\Simulations;

interface IndexesSimulations
{
    /**
     * Display all available Simulations.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index(mixed $user);
}
