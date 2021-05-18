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

    /**
     * Redirect to a specific page after updating an existing source.
     * //TODO
     */
    public function redirectTo();
}
