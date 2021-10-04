<?php

namespace App\Actions\Embers\MapData;

use App\Contracts\Embers\MapData\IndexesMapData;
use App\Models\User;
use Illuminate\Support\Arr;

class IndexMapData implements IndexesMapData
{
    /**
     * Display all the available map data.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    public function index(User $user): array
    {
        $data = User::query()->whereId($user->id)->first()->toArray();

        return Arr::only($data, 'data');
    }
}
