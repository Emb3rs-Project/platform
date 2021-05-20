<?php

namespace App\Contracts\Embers\Simulations;

use App\Models\Project;

interface IndexesSimulations
{
    /**
     * Display all available Simulations.
     *
     * @param  mixed  $user
     * @param  int  $projectId
     * @return mixed
     */
    public function index(mixed $user, int $projectId);
}
