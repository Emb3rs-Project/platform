<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\StoresProjects;
use App\EmbersPermissionable;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class StoreProject implements StoresProjects
{
    use EmbersPermissionable;

    /**
     * Validate and create a new Link.
     *
     * @param  mixed  $user
     * @param  mixed  $user
     * @param  array  $input
     * @return Project
     */
    public function store($user, array $input)
    {
        $this->authorize($user);

        $this->validate($input);

        $link = $this->save($user, $input);

        return $link;
    }

    /**
     * Validate the create Project operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['filled', 'string'],
            'location_id' => ['required', 'integer', 'numeric', 'exists:locations,id']
        ])
            ->validate();
    }

    /**
     * Save the Project in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Project
     */
    protected function save($user, array $input)
    {
        $project = Project::create([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'location_id' =>  $input['location_id']
        ]);

        $project->teams()->attach($user->currentTeam);

        return $project;
    }
}
