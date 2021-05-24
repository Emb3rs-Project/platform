<?php

namespace App\Contracts\Embers\Projects;

interface DestroysProjects
{
    /**
     * Delete an existing Project.
     *
     * @param  int  $id
     * @return mixed
     */
    public function destroy(int $id);
}
