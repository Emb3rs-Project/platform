<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\Instance;

interface UpdatesSinks
{
    /**
     * Validate and update an existing Sink.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @param  array  $input
     * @return Instance
     */
    public function update(mixed $user, int $id, array $input);
}
