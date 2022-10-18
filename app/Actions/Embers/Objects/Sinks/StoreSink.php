<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\EmbersPermissionable;
use App\HasEmbersProperties;
use App\Models\Instance;
use App\Models\Location;
use App\Models\User;
use App\Rules\Coordinates;
use App\Rules\Prohibit;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreSink implements StoresSinks
{
    use EmbersPermissionable, HasEmbersProperties;

    /**
     * Validate and create a new Sink.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return \App\Models\Instance
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(User $user, array $input): Instance
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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input): array
    {
        $validator = Validator::make($input, [
            'sink' => ['required', 'array:data'],
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
            'sink.data.name' => [
                'regex:/[a-z]/',      // must contain at least one lowercase letter
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

        // $this->checkIfPropertiesAreValid($validated);

        // return $validated;
        return $validated;
    }

    /**
     * Save the Sink in the DB.
     *
     * @param  \App\Models\User  $user
     * @param  array  $validated
     * @return \App\Models\Instance
     */
    protected function save($user, array $validated): Instance
    {
        $newInstance = [
            'name' => Arr::get($validated, 'sink.data.name') ?? 'Not Defined',
            'values' => Arr::get($validated, 'sink.data'),
            'template_id' => Arr::get($validated, 'template_id'),
            'location_id' => Arr::get($validated, 'location_id')
        ];

        if (!is_null(Arr::get($validated, 'location'))) {
            // A new location was selected to be used for this Sink
            $location = Location::create([
                'name' => Arr::get($newInstance, 'name'),
                'type' => 'point',
                'data' => [
                    "center" => [
                        Arr::get($validated, 'location.lat'),
                        Arr::get($validated, 'location.lng')
                    ]
                ]
            ]);

            Arr::set($newInstance, 'location_id', $location->id);
        }

        $instance = Instance::create($newInstance);
        $instance->teams()->attach($user->currentTeam);

        app(CharacterizesInstances::class)->characterize($instance);

        return $instance;
    }
}
