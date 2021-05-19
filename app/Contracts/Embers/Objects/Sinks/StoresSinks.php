<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface StoresSinks
{
    /**
     * Validate and create a new Sink.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store(mixed $user, array $input);
}
