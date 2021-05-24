<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class StoreSource implements StoresSources
{
    /**
     * Validate and create a new instance.
     *
     * @param  array  $input
     * @return Instance
     */
    public function store(array $input)
    {
        Gate::authorize('create', Instance::class);

        $this->validate($input);

        $source = $this->save($input);

        return $source;
    }

    /**
     * Validate the create Source operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        Validator::make($input, [
            'source' => ['filled', 'array'],
            'source.name' => ['filled', 'string', 'max:255'],
            'equipments' => ['filled', 'array'],
            'equipments.*.key' => ['required', 'string', 'exists:categories,id'],
            'processes' => ['filled', 'array'],
            'processes.*.key' => ['required', 'string', 'exists:categories,id'],
            'template_id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            // // 'location_id' => ['required_without:location' ,'string', 'exists:locations,id'],
            // // 'location' => ['required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['required'], // for now, later remove current line and uncomment 2 above
        ])->validate();
    }

    /**
     * Save the Source in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Instance
     */
    protected function save(array $input)
    {
        // TODO: attach the user id to the entity

        // TODO: save processes

        $source = $input['source'];
        $equipments = $input['equipments'];
        foreach ($equipments as $key => $value) {
            unset($equipments[$key]['template']);
        }

        $processes = $input['processes'];
        foreach ($processes as $key => $value) {
            unset($processes[$key]['template']);
        }

        $newInstance = [
            "name" => 'Not Defined',
            "values" => [
                "equipments" => $equipments,
                "processes" => $processes,
                "info" => $source
            ],
            "template_id" => $input['template_id'],
            "location_id" => null
        ];

        // Check if Property Name Exists
        if (!empty($source['name'])) {
            $newInstance['name'] = $source['name'];
        }

        if (is_array($input["location_id"])) {
            $marker = $input["location_id"];
            $location = Location::create([
                'name' => $newInstance['name'],
                'type' => 'point',
                'data' => [
                    "center" => [$marker["lat"], $marker["lng"]]
                ]
            ]);
            $newInstance['location_id'] = $location->id;
        } else {
            // Check if Location is Set
            $newInstance['location_id'] = $input['location_id'];
        }

        $instance = Instance::create($newInstance);
        $instance->teams()->attach(Auth::user()->currentTeam);

        return $instance;
    }
}
