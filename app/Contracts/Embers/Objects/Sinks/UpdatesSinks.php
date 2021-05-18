<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\Instance;

interface UpdatesSinks
{
    /**
     * Validate and update an existing Sink.
     *
     * @param  mixed  $user
     * @param  int    $sink
     * @param  array  $input
     * @return Instance
     */
    public function update(mixed $user, int $id, array $input);

    /**
     * Redirect to a specific page after updating an existing sink.
     * //TODO
     */
    public function redirectTo();
}
