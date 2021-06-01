<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface CreatesSinks
{
    /**
     * Display the necessary objects for the creation of a Sink.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user);
}
