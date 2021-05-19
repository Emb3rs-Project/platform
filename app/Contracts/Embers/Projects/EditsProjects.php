<?php

namespace App\Contracts\Embers\Projects;

interface EditsProjects
{
    /**
     * Display the necessary objects for updating a given Project.
     *
     * @param  mixed  $user
     * @param  int    $id
     * @return mixed
     */
    public function edit(mixed $user, int $id);
}
