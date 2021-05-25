<?php

namespace App\Contracts\Embers\Simulations;

use App\Models\Project;

interface IndexesSimulations
{
    /**
     * Display all available Simulations.
     *
     * @param  int  $projectId
     * @return mixed
     */
    public function index(int $projectId);
}
