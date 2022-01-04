<?php

namespace App\Contracts\Embers\Integration;

interface ReportsSimulationFinishes
{
    public function report(array $input): void;
}
