<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\Instance;

interface ShowsSources
{
    /**
     * Display the given Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     */
    public function show($user, int $id);
}
