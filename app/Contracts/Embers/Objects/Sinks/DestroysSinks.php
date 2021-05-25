<?php

namespace App\Contracts\Embers\Objects\Sinks;

interface DestroysSinks
{
    /**
     * Delete an existing Sink.
     *
     * @param  int  $id
     * @return mixed
     */
    public function destroy(int $id);
}
