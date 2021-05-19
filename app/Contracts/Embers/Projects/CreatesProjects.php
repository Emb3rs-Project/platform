<?php

namespace App\Contracts\Embers\Projects;

interface CreatesProjects
{
    /**
     * Display the necessary objects for the creation of a Project.
     *
     * @return mixed
     */
    public function create();
}
