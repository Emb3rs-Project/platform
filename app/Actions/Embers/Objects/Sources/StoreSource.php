<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\EmbersPermissionable;
use App\HasEmbersProperties;
use App\Models\Instance;
use App\Models\Location;
use App\Rules\Coordinates;
use App\Rules\Prohibit;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreSource implements StoresSources
{
    use EmbersPermissionable, HasEmbersProperties;

    /**
     * Validate and create a new instance.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Instance
     */
    public function store($user, array $input)
    {
        $this->authorize($user);

        $validated = $this->validate($input);

        $source = $this->save($user, $validated);

        return $source;
    }

    /**
     * Validate the create Source operation.
     *
     * @param  array  $input
     * @return array
     */
    protected function validate(array $input)
    {
        $validator = Validator::make($input, [
            'source' => ['required', 'array:data'],
            'equipment' => ['present', 'array'],
            'equipment.*.id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'equipment.*.category_id' => ['required', 'integer', 'numeric', 'exists:categories,id'],
            'equipment.*.data' => ['required', 'array'],
            'processes' => ['present', 'array'],
            'processes.*.id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'processes.*.category_id' => ['required', 'integer', 'numeric', 'exists:categories,id'],
            'processes.*.data' => ['required', 'array'],
            'template_id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
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

        $this->checkIfPropertiesAreValid($validated);

        return $validated;
    }

    /**
     * Save the Source in the DB.
     *
     * @param  mixed  $user
     * @param  array  $validated
     * @return Instance
     */
    protected function save($user, array $validated)
    {
        $newInstance = [
            'name' => Arr::get($validated, 'source.data.name') ?? 'Not Defined',
            'values' => Arr::get($validated, 'source.data'),
            'equipment' => Arr::get($validated, 'equipments'),
            'processes' => Arr::get($validated, 'processes'),
            'template_id' => Arr::get($validated, 'template_id'),
            'location_id' => Arr::get($validated, 'location_id')
        ];

        if (!is_null(Arr::get($validated, 'location'))) {
            // A new location was selected to be used for this Source
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

        return $instance;
    }
}
