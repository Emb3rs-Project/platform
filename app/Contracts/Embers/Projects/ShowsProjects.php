<?php

namespace App\Contracts\Embers\Projects;

interface ShowsProjects
{
    /**
     * Display the given Projects.
     *
     * @param  int  $id
     */
    public function show(int $id);
}
