<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface DestroysSinks
{
    /**
     * Delete an existing Sink.
     *
     * @param  mixed   $user
     * @param  string  $id
     * @return mixed
     */
    public function destroy(mixed $user, string $id);
}
