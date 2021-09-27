<?php

namespace App\Contracts\Embers\MapData;

use App\Models\User;

interface IndexesMapData
{
    /**
     * Display all the available map data.
     */
    public function index(User $user): array;
}
