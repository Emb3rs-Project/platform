<?php

namespace App\Contracts\Embers\Objects\Sinks;

use App\Models\Instance;

interface UpdatesSinks
{
    /**
     * Validate and update an existing Sink.
     *
     * @param  int  $id
     * @param  array  $input
     * @return Instance
     */
    public function update(int $id, array $input);
}
