<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface SharesSinks
{
    /**
     * Share a given Sink.
     *
     * @param  int  $id
     * @return mixed
     */
    public function share(int $id);
}
