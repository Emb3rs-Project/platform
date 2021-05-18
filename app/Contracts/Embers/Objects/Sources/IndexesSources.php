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

    /**
     * Redirect to a specific page after updating an existing source.
     * //TODO
     */
    public function redirectTo();
}
