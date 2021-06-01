<?php

namespace App\Contracts\Embers\Projects;

interface SharesProjects
{
    /**
     * Share a given Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id);
}
