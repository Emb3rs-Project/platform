<?php

namespace App\Contracts\Embers\Projects;

interface StoresProjects
{
    /**
     * Validate and create a new Project.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store($user, array $input);
}
