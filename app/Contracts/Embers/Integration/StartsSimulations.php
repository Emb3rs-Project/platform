<?php

namespace App\Contracts\Embers\Integration;

use App\Models\Instance;
use App\Models\Simulation;
use App\Models\SimulationSession;

interface StartsSimulations
{
    public function run_simulation(SimulationSession $instance): void;
}
