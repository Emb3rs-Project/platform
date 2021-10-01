<?php

namespace App\Contracts\Embers\MapData;

use App\Models\User;

interface IndexesMapData
{
    /**
     * Display all the available map data.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function index(User $user): array;
}
