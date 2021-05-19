<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\UpdatesLinks;
use App\Models\GeoSegment;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UpdateLink implements UpdatesLinks
{
    /**
     * Validate, update and return an existing instance.
     *
     * @param  mixed  $user
     * @param  int    $sink
     * @param  array  $input
     * @return Instance
     */
    public function update(mixed $user, int $id, array $input)
    {
        $link = Link::with(['geoSegments'])->findOrFail($id);

        Gate::authorize('update', $link);

        $this->validate($input);

        $link = $this->save($user, $link, $input);

        return $link;
    }

    /**
     * Validate the create Sink operation.
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
     * Save the Sink in the DB.
     *
     * @param  mixed    $user
     * @param  Instance $sink
     * @param  array    $input
     * @return Instance
     */
    protected function save(mixed $user, Link $link, array $input)
    {
        $segments = $input['locationData']['segments'];

        $link->name = $input['name'];
        $link->description = $input['description'] ?? null;

        foreach ($segments as $data) {
            $segment = GeoSegment::create([
                'data' => $data
            ]);

            $link->geoSegments()->attach($segment);
        }

        return $link;
    }
}
