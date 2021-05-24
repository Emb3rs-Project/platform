<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\Instance;

interface ShowsSources
{
    /**
     * Display the given Source.
     *
     * @param  int  $id
     */
    public function show(int $id);
}
