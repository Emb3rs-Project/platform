<?php

namespace App\Contracts\Embers\Objects;

use App\Models\Instance;

interface UpdatesSinks
{
    /**
     * Validate and update an existing sink.
     *
     * @param  mixed    $user
     * @param  Instance $sink
     * @param  array    $input
     * @return mixed
     */
    public function update(mixed $user, Instance $sink, array $input);

    /**
     * Redirect to a specific page after updating an existing sink.
     * //TODO
     */
    public function redirectTo();
}
