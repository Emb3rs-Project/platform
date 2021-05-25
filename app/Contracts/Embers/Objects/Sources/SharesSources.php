<?php

namespace App\Contracts\Embers\Objects\Sources;

interface SharesSources
{
    /**
     * Share a given Source.
     *
     * @param  int  $id
     * @return mixed
     */
    public function share(int $id);
}
