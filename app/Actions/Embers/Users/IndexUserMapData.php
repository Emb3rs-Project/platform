<?php

namespace App\Actions\Embers\Users;

use App\Contracts\Embers\Users\IndexesUsersMapData;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class IndexUserMapData implements IndexesUsersMapData
{
    /**
     * Display all the available map data for the currently logged in user.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user)
    {
        $data = User::whereId($user->id)->first()->toArray();

        return Arr::only($data, 'data');
    }
}
