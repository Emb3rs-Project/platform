<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface DestroysSinks
{
    /**
     * Delete an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function destroy($user, int $id);
}
