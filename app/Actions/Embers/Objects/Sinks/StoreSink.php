<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\EmbersPermissionable;
use App\HasEmbersProperties;
use App\Models\Instance;
use App\Models\Location;
use App\Rules\Coordinates;
use App\Rules\Prohibit;
use App\Rules\Property;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreSink implements StoresSinks
{
    use EmbersPermissionable;
    use HasEmbersProperties;

    /**
     * Validate and create a new Sink.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Instance
     */
    public function store($user, array $input)
    {
        $this->authorize($user);

        $validated = $this->validate($input);

        $sink = $this->save($user, $validated);

        return $sink;
    }

    /**
     * Validate the create Sink operation.
     *
     * @param  array  $input
     * @return array
     */
    protected function validate(array $input)
    {
        $validator = Validator::make($input, [
            'sink' => ['required', 'array:data'],
            'sink.data.*' => [new Property],
            'template_id' => ['required', 'numeric', 'integer', 'exists:templates,id'],
            'location_id' => [
                Rule::requiredIf(function () use ($input) {
                    return !Arr::has($input, 'location') || Arr::get($input, 'location') === null;
                }),
                new Prohibit($input, 'location'),
                'nullable',
                'numeric',
                'integer',
                'exists:locations,id'
            ],
            'location' => [
                Rule::requiredIf(function () use ($input) {
                    return !Arr::has($input, 'location_id') || Arr::get($input, 'location_id') === null;
                }),
                new Prohibit($input, 'location_id'),
                'nullable',
                'array:lat,lng',
            ],
            'location.lat' => ['required_with:location', 'numeric', new Coordinates],
            'location.lng' => ['required_with:location', 'numeric', new Coordinates],
        ]);

        $validated = $validator->validate();

        $this->checkIfPropertiesBelongToTemplate($validated, null);

        return $validated;
    }

    /**
     * Save the Sink in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Instance
     */
    protected function save($user, array $input)
    {
        $newInstance = [
            'name' => Arr::get($input, 'sink.data.name') ?? 'Not Defined',
            'values' => [],
            'template_id' => Arr::get($input, 'template_id'),
            'location_id' => Arr::get($input, 'location_id')
        ];

        if (Arr::get($input, 'location')) {
            // A new location was selected to be used for this Sink
            $location = Location::create([
                'name' => Arr::get($newInstance, 'name'),
                'type' => 'point',
                'data' => [
                    "center" => [
                        Arr::get($input, 'location.lat'),
                        Arr::get($input, 'location.lng')
                    ]
                ]
            ]);

            Arr::set($newInstance, 'location_id', $location->id);
        }

        $instance = Instance::create($newInstance);
        $instance->teams()->attach($user->currentTeam);

        return $instance;
    }
}
