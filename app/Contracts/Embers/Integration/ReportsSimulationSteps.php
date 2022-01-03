<?php

namespace App\Contracts\Embers\Integration;

interface ReportsSimulationSteps
{
    public function report(array $input): void;
}
