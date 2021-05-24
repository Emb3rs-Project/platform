<?php

namespace App\Contracts\Embers\Objects\Sources;

use App\Models\Instance;

interface IndexesSources
{
    /**
     * Display all existing sources.
     *
     * @return mixed
     */
    public function index();
}
