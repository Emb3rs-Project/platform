<?php

namespace App\Contracts\Embers\Users;

interface IndexesUsersMapData
{
    /**
     * Display all the available map data for the currently logged in user.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user);
}
