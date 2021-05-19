<?php

namespace App\Contracts\Embers\Objects\Projects;

interface ShowsProjects
{
    /**
     * Display the given Projects.
     *
     * @param  mixed  $user
     * @param  int    $id
     */
    public function show(mixed $user, int $id);
}
