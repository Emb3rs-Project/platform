<?php

namespace App\Contracts\Embers\Projects;

interface ShowsProjects
{
    /**
     * Display the given Projects.
     *
     * @param  mixed  $user
     * @param  int  $id
     */
    public function show($user, int $id);
}
