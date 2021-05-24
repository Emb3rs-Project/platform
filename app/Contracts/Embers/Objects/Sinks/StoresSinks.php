<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface StoresSinks
{
    /**
     * Validate and create a new Sink.
     *
     * @param  array  $input
     * @return mixed
     */
    public function store(array $input);
}
