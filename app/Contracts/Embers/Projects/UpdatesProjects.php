<?php
namespace App\Contracts\Embers\Projects;

interface UpdatesProjects
{
    /**
     * Validate and update an existing Project.
     *
     * @param  int  $id
     * @param  array  $input
     * @return mixed
     */
    public function update(int $id, array $input);
}
