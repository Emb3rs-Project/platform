<?php

namespace App\Contracts\Embers\Objects\Projects;

interface UpdatesProjects
{
    /**
     * Validate and update an existing Project.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @param  array  $input
     * @return mixed
     */
    public function update($user, int $id, array $input);
}
