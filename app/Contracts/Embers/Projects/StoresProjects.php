<?php

namespace App\Contracts\Embers\Projects;

interface StoresProjects
{
    /**
     * Validate and create a new Project.
     *
     * @param  array  $input
     * @return mixed
     */
    public function store(array $input);
}
