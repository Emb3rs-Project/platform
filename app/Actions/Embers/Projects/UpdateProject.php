<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\UpdatesProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UpdateProject implements UpdatesProjects
{
    use EmbersPermissionable;

    /**
     * Validate, update and return an existing Project.
     */
    public function update(User $user, int $id, array $input): Project
    {
        $this->authorize($user);

        $project = Project::findOrFail($id);

        $validated = $this->validate($input);

        $project = $this->save($project, $validated);

        return $project;
    }

    /**
     * Validate the create Project operation.
     */
    protected function validate(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => ['filled', 'string', 'max:255'],
            'description' => ['filled', 'string'],
            'location_id' => ['filled', 'string', 'exists:locations,id']
        ]);

        return $validator->validate();
    }

    /**
     * Save the Project in the DB.
     */
    protected function save(Project $project, array $validated): Project
    {
        if (!empty($validated['name'])) {
            $project->name = $validated['name'];
        }

        if (!empty($validated['description'])) {
            $project->name = $validated['description'];
        }

        if (!empty($validated['location_id'])) {
            $project->name = $validated['location_id'];
        }

        $project->save();

        return $project;
    }
}
