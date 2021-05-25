<?php

namespace App\Contracts\Embers\Projects;

interface SharesProjects
{
    /**
     * Share a given Project.
     *
     * @param  int  $id
     * @return mixed
     */
    public function share(int $id);
}
