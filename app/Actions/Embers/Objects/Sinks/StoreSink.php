<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\Location;
use App\Models\TemplateProperty;
use App\Rules\Coordinates;
use App\Rules\Prohibit;
use App\Rules\Property;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class StoreSink implements StoresSinks
{
    use EmbersPermissionable;

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
    protected function validate(array $input): array
    {
        $validator = Validator::make($input, [
            'sink' => ['required', 'array'],
            'sink.data.*' => [new Property],
            'equipments.*.key' => ['required', 'string', 'exists:instances,id'],
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
                'array'
            ],
            'location.lat' => ['required_with:location', 'numeric', new Coordinates],
            'location.lng' => ['required_with:location', 'numeric', new Coordinates],
        ]);

        return $validator->validate();
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
            'name' => Arr::get($input, 'sink.data.name') ?: 'Not Defined',
            'values' => [
                Arr::get($input, 'equipments')
            ],
            "template_id" => Arr::get($input, 'template_id'),
        ];

        if (Arr::has($input, 'location')) {
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

            Arr::add($newInstance, 'location_id', $location->id);
        } else if (Arr::has($input, 'location_id')) {
            // An old location was selected to be used for this Sink
            Arr::add($newInstance, 'location_id', Arr::get($input, 'location_id'));
        }

        $instance = Instance::create($newInstance);
        $instance->teams()->attach($user->currentTeam);

        return $instance;
    }
}
