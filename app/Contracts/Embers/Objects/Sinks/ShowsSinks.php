<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface ShowsSinks
{
    /**
     * Display the given Sink.
     *
     * @param  int  $id
     * @return mixed
     */
    public function show(int $id);
}
