<?php

namespace App\Contracts\Embers\Objects\Sources;

interface SharesSources
{
    /**
     * Share a given Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id);
}
