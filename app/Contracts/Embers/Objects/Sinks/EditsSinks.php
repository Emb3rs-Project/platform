<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface EditsSinks
{
    /**
     * Display the necessary objects for updating a given Sink.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function edit(mixed $user, int $id);

    /**
     * Redirect to a specific page after updating an existing source.
     * //TODO
     */
    public function redirectTo();
}
