<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\EmbersPermissionable;
use App\Models\GeoSegment;
use App\Models\Link;
use App\Rules\Coordinates;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

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

        $validated = $this->validate($input);

        $link = $this->save($user, $validated);

        return $link;
    }

    /**
     * Validate the create Link operation.
     *
     * @param  array  $input
     * @return array
     */
    protected function validate(array $input)
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['filled', 'string', 'max:255'],
            // 'segments' => ['required', 'array'],
            'segments.*.from' => ['required', 'numeric', new Coordinates],
            'segments.*.to' => ['required', 'numeric', new Coordinates],
            'segments.*.distance' => ['required', 'numeric'],
            'segments.*.data' => ['required'],
        ]);

        return $validator->validate();
    }

    /**
     * Save the Link in the DB.
     *
     * @param  mixed  $user
     * @param  array  $validated
     * @return Link
     */
    protected function save($user, array $validated)
    {
        $link = Link::create([
            'name' => Arr::get($validated, 'name'),
            'description' => Arr::get($validated, 'description'),
        ]);

        $link->teams()->attach($user->currentTeam);

        $segments = Arr::get($validated, 'segments') ?? [];

        foreach ($segments as $data) {
            $segment = GeoSegment::create([
                'data' => $data
            ]);

            $link->geoSegments()->attach($segment);
        }

        return $link;
    }
}
