<?php

namespace App\Contracts\Embers\Objects\Projects;

interface DestroysProjects
{
    /**
     * Delete an existing Project.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function destroy(mixed $user, int $id);
}
