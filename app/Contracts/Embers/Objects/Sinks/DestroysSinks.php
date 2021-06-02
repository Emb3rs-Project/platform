<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface DestroysSinks
{
    /**
     * Delete an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id);
}
