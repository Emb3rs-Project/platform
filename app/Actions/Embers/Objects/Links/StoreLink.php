<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\Models\GeoSegment;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class StoreLink implements StoresLinks
{
    /**
     * Validate and create a new Link.
     *
     * @param  array $input
     * @return Instance
     */
    public function store(array $input)
    {
        Gate::authorize('create', Link::class);

        $this->validate($input);

        $link = $this->save($input);

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
            'locationData.segments.*.data.to.*' => ['required', 'numeric'],
            'locationData.segments.*.data.from.*' => ['required', 'numeric'],
            'description' => ['filled','string']
        ])
        ->validate();
    }

    /**
     * Save the Link in the DB.
     *
     * @param  array  $input
     * @return void
     */
    protected function save(array $input)
    {
        $segments = $input['locationData']['segments'];

        $link = Link::create([
            'name' => $input['name'],
            'description' => $input['description']
        ]);

        $link->teams()->attach(Auth::user()->currentTeam);

        foreach ($segments as $data) {
            $segment = GeoSegment::create([
                'data' => $data
            ]);

            $link->geoSegments()->attach($segment);
        }

        return $link;
    }
}
