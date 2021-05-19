<?php

namespace App\Contracts\Embers\Objects\Sources;

interface DestroysSources
{
    /**
     * Delete an existing Source.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function destroy(mixed $user, int $id);
}
