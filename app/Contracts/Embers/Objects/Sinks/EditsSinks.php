<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface EditsSinks
{
    /**
     * Display the necessary objects for updating a given Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function edit($user, int $id);
}
