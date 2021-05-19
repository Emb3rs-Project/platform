<?php

namespace App\Contracts\Embers\Objects\Projects;

interface StoresProjects
{
    /**
     * Validate and create a new Project.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store(mixed $user, array $input);
}
