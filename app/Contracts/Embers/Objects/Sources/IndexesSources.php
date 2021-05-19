<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\Instance;

interface IndexesSources
{
    /**
     * Display all existing sources.
     *
     * @param  mixed    $user
     * @return mixed
     */
    public function index(mixed $user);
}
