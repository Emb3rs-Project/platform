<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\Models\GeoObject;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class StoreSource implements StoresSources
{
    /**
     * Validate and create a new instance.
     *
     * @param  mixed $user
     * @param  array $input
     * @return mixed
     */
    public function store($user, $input)
    {
        Gate::authorize('create', Instance::class);

        $this->validate($input);

        $source = $this->save($user, $input);

        return $source;
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
            'source' => ['required', 'array'],
            'source.data.name' => ['filled', 'string', 'max:255'],
            'equipments' => ['required', 'array'],
            'equipments.*.id' => ['required', 'string', 'exists:categories,id'],
            'processes' => ['required', 'array'],
            'template_id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            // 'location_id' => ['required_without:location' ,'string', 'exists:locations,id'],
            // 'location' => ['required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['required'], // for now, later remove current line and uncomment 2 above
        ])
        ->validate();
    }

    /**
     * Save the Source in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function save(mixed $user, array $input): Instance
    {
        // TODO: attach the user id to the entity

        // TODO: save processes

        $source = $input['source'];
        $equipments = $input['equipments'];
        foreach ($equipments as $key => $value) {
            unset($equipments[$key]['template']);
        }

        $newInstance = [
            "name" => 'Not Defined',
            "values" => [
                "equipments" => $equipments,
                "info" => $source
            ],
            "template_id" => $input['template_id'],
            "location_id" => null
        ];

        // Check if Property Name Exists
        if (!empty($source['data']['name'])) {
            $newInstance['name'] = $source['data']['name'];
        }

        if (is_array($input["location_id"])) {
            $marker = $input["location_id"];
            $geo = GeoObject::create([
                'type' => 'point',
                'data' => [
                    "center" => [$marker["lat"], $marker["lng"]]
                ]
            ]);

            $location = Location::create([
                'name' => $newInstance['name'],
                'geo_object_id' => $geo->id
            ]);
            $newInstance['location_id'] = $location->id;
        } else {
            // Check if Location is Set
            $locationId = $input['location_id'];
            if ($locationId) {
                $newInstance['location_id'] = $locationId;
            }
        }

        $instance = Instance::create($newInstance);
        $instance->teams()->attach($user->currentTeam);

        return $instance;
    }

    public function redirectTo()
    {
        //TODO
    }
}
