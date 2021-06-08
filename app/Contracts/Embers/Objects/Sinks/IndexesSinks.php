<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface IndexesSinks
{
    /**
     * Display all available Sinks.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index($user);
}
