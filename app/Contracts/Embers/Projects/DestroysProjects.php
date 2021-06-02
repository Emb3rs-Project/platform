<?php

namespace App\Contracts\Embers\Projects;

interface DestroysProjects
{
    /**
     * Delete an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id);
}
