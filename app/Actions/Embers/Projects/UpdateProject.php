<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\UpdatesProjects;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UpdateProject implements UpdatesProjects
{
    /**
     * Validate, update and return an existing Project.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return Instance
     */
    public function update($user, int $id, array $input)
    {
        $project = Project::findOrFail($id);

        Gate::authorize('update', $project);

        $this->validate($input);

        $project = $this->save($project, $input);

        return $project;
    }

    /**
     * Validate the create Link operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        Validator::make($input, [
            'name' => ['filled', 'string', 'max:255'],
            'description' => ['filled', 'string'],
            'location_id' => ['filled', 'string', 'exists:locations,id']
        ])
        ->validate();
    }

    /**
     * Save the Link in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Project
     */
    protected function save(Project $project, array $input)
    {
        if (!empty($input['name'])) {
            $project->name = $input['name'];
        }

        if (!empty($input['description'])) {
            $project->name = $input['description'];
        }

        if (!empty($input['location_id'])) {
            $project->name = $input['location_id'];
        }

        $project->save();

        return $project;
    }
}
