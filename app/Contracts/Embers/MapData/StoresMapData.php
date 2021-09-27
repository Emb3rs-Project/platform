<?php

namespace App\Contracts\Embers\MapData;

use App\Models\User;

interface StoresMapData
{
    /**
     * Validate and create new map data.
     */
    public function store(User $user, array $input): array;
}
