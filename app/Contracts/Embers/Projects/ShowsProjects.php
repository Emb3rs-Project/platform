<?php

namespace App\Contracts\Embers\Projects;

use App\Models\Project;
use App\Models\User;

interface ShowsProjects
{
    /**
     * Display the given Projects.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return \App\Models\Project
     */
    public function show(User $user, int $id): Project;
}
