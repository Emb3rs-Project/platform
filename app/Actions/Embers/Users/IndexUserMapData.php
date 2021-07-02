<?php

namespace App\Actions\Embers\Users;

use App\Contracts\Embers\Users\IndexesUsersMapData;
use App\Models\User;
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

        // Its really annoying when it returns the wrong thing whene it shouldnt
        $data = User::whereId($user->id)->get();

        Log::alert($data);

        return;
    }
}
