<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\EmbersPermissionable;
use App\Models\GeoSegment;
use App\Models\Link;

class StoreLink implements StoresLinks
{
    use EmbersPermissionable;

    /**
     * Validate and create a new Link.
     *
     * @param  mixed  $user
     * @param  array $input
     * @return mixed
     */
    public function store($user, array $input)
    {
        $this->authorize($user);

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
        // Validator::make($input, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'segments.*.data.to.*' => ['required', 'numeric'],
        //     'segments.*.data.from.*' => ['required', 'numeric'],
        //     'description' => ['filled','string']
        // ])
        // ->validate();
    }

    /**
     * Save the Link in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Link
     */
    protected function save($user, array $input)
    {
        $segments = $input['segments'];

        $link = Link::create([
            'name' => $input['name'],
            // 'description' => $input['description']
        ]);

        $link->teams()->attach($user->currentTeam);

        foreach ($segments as $data) {
            $segment = GeoSegment::create([
                'data' => $data
            ]);

            $link->geoSegments()->attach($segment);
        }

        return $link;
    }
}
