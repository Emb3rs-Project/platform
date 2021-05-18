<?php

namespace App\Contracts\Embers\Objects\Sources;

interface CreatesSources
{
    /**
     * Display the necessary objects for the creation of a Source.
     *
     * @return mixed
     */
    public function create();

    /**
     * Redirect to a specific page after creating a new source
     * //TODO
     */
    public function redirectTo();
}
