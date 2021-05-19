<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface ShowsSinks
{
    /**
     * Display the given Sink.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function show(mixed $user, int $id);
}
