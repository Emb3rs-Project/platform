<?php

namespace App\Actions\Embers\Projects;

use App\Contracts\Embers\Projects\StoresProjects;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class StoreProject implements StoresProjects
{
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
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'store-project'), 401);

        $this->validate($input);

        $link = $this->save($user, $input);

        return $link;
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['filled', 'string'],
            'location_id' => ['required', 'integer', 'numeric', 'exists:locations,id']
        ])
        ->validate();
    }

    /**
     * Save the Link in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function save($user, array $input)
    {
        $project = Project::create([
            'name' => $input['name'],
            'description' => $input['description'] ?? null,
            'location_id' =>  $input['location_id']
        ]);

        $project->teams()->attach($user->currentTeam);
    }
}
