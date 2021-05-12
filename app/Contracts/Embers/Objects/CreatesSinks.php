<?php

namespace App\Contracts\Embers\Objects;

interface CreatesSinks
{
    /**
     * Validate and create a new sink. //@geocfu TODO: maybe add the team variable here but idk for now
     *
     * @param  mixed  $user
     * @return mixed  $sink
     */
    public static function create($user, $sink);
}
