<?php

namespace App\Contracts\Embers\Projects;

use App\Models\Project;
use App\Models\User;

interface SharesProjects
{
    /**
     * Share a given Project.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return \App\Models\Project
     */
    public function share(User $user, int $id): Project;
}
