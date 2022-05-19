<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\StoresProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class StoreProject implements StoresProjects
{
    use EmbersPermissionable;

    /**
     * Validate and create a new Link.
     */
    public function store(User $user, array $input): Project
    {
        $this->authorize($user);

        $validated = $this->validate($input);

        $link = $this->save($user, $validated);

        return $link;
    }

    /**
     * Validate the create Project operation.
     */
    protected function validate(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            '_data' => ['required']
        ]);

        return $validator->validate();
    }

    /**
     * Save the Project in the DB.
     */
    protected function save(User $user, array $validated): Project
    {
        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'data' =>  $validated['_data']
        ]);

        $project->teams()->attach($user->currentTeam);

        return $project;
    }
}
