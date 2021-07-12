<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\Location;
use App\Rules\Coordinates;
use App\Rules\Property;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

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

        $this->validate($input);

        $sink = $this->save($user, $input);

        return $sink;
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
            'sink' => ['required', 'array'],
            'sink.data.*' => [new Property],
            // 'sink.data.name' => ['filled', 'string', 'max:255'],
            'equipments.*.key' => ['required', 'string', 'exists:instances,id'],
            'template_id' => ['required', 'numeric', 'integer', 'exists:templates,id'],
            'location_id' => ['prohibited_if:location', 'numeric', 'integer', 'exists:locations,id'],
            'location' => ['required_without:location_id', 'array'],
            'location.lat' => ['required_with:location', 'numeric', new Coordinates],
            'location.lng' => ['required_with:location', 'numeric', new Coordinates],
        ])->validate();
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
            "name" => 'Not Defined',
            "values" => [
                "equipments" => []
            ],
            "template_id" => Arr::get($input, 'template_id'),
            "location_id" => null
        ];

        // Check if Property Name Exists
        if (Arr::has($input, 'sink.data.name')) {
            $newInstance['name'] = Arr::get($input, 'sink.data.name');
        }

        if (Arr::has($input, 'equipments')) {
            $newInstance['name']['equipments'] = Arr::get($input, 'equipments');
        }

        if (Arr::has($input, 'location')) {
            // A new location was selected to be used for this Sink
            $location = Location::create([
                'name' => $newInstance['name'],
                'type' => 'point',
                'data' => [
                    "center" => [
                        Arr::get($input, 'location.lat'),
                        Arr::get($input, 'location.lng')
                    ]
                ]
            ]);
            $newInstance['location_id'] = $location->id;
        } else {
            // An old location was selected to be used for this Sink
            $newInstance['location_id'] = Arr::get($input, 'location_id');
        }

        // if (is_array($input["location_id"])) {
        //     $marker = $input["location_id"];

        //     $location = Location::create([
        //         'name' => $newInstance['name'],
        //         'type' => 'point',
        //         'data' => [
        //             "center" => [$marker["lat"], $marker["lng"]]
        //         ]
        //     ]);
        //     $newInstance['location_id'] = $location->id;
        // } else {
        //     $newInstance['location_id'] = $input['location_id'];
        // }

        $instance = Instance::create($newInstance);
        $instance->teams()->attach($user->currentTeam);

        return $instance;
    }
}
