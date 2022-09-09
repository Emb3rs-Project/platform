<?php

namespace App\Contracts\Embers\Integration;

use App\Models\Instance;

interface CharacterizesInstances
{
    public function characterize(Instance $instance);
}
