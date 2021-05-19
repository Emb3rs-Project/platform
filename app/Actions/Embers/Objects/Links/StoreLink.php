<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\Models\GeoSegment;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class StoreLink implements StoresLinks
{
    /**
     * Validate and create a new Link.
     *
     * @param  mixed $user
     * @param  array $input
     * @return mixed
     */
    public function store(mixed $user, array $input)
    {
        Gate::authorize('create', Link::class);

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
            'locationData.segments' => ['required', 'array'],
            'description' => ['string']
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
    protected function save(mixed $user, array $input)
    {
        $segments = $input['locationData']['segments'];

        $link = Link::create([
            'name' => $input['name'],
            'description' => $input['description']
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

    public function redirectTo()
    {
        //TODO
    }
}
