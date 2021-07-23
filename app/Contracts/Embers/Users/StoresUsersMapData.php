<?php

namespace App\Contracts\Embers\Users;

interface StoresUsersMapData
{
    /**
     * Validate and create new map data for the currently logged in user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store($user, array $input);
}
