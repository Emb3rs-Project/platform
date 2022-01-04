<?php

namespace App\Contracts\Embers\Integration;

interface ReportsCharacterizationFinishes
{
    public function report(array $input): void;
}
