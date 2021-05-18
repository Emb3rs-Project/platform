<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface CreatesSinks
{
    /**
     * Display the necessary objects for the creation of a Sink.
     *
     * @return mixed
     */
    public function create();

    /**
     * Redirect to a specific page after creating a new sink.
     * //TODO
     */
    public function redirectTo();
}
