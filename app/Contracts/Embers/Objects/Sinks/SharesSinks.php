<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface SharesSinks
{
    /**
     * Share a given Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id);
}
