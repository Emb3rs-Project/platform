<?php

namespace App\Contracts\Embers\Projects;

interface IndexesProjects
{
    /**
     * Display all available Projects.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function index(mixed $user);
}
