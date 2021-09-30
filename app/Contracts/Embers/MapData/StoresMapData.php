<?php

namespace App\Contracts\Embers\MapData;

use App\Models\User;

interface StoresMapData
{
    /**
     * Validate and create new map data.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return array
     */
    public function store(User $user, array $input): array;
}
