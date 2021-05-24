<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class StoreSink implements StoresSinks
{
    /**
     * Validate and create a new Sink.
     *
     * @param  array  $input
     * @return Instance
     */
    public function store(array $input)
    {
        Gate::authorize('create', Instance::class);

        $this->validate($input);

        $sink = $this->save($input);

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
            'sink.data.name' => ['filled', 'string', 'max:255'],
            // 'equipments' => ['present'], // Will change later
            'template_id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            // 'location_id' => ['required_without:location' ,'string', 'exists:locations,id'],
            // 'location' => ['required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['required'], // for now, later remove current line and uncomment 2 above
        ])->validate();
    }

    /**
     * Save the Sink in the DB.
     *
     * @param  array  $input
     * @return Instance
     */
    protected function save(array $input)
    {
        $sink = $input['sink'];
        $templateId = $input['template_id'];

        $newInstance = [
            "name" => 'Not Defined',
            "values" => [
                "equipments" => []
            ],
            "template_id" => $templateId,
            "location_id" => null
        ];

        // Check if Property Name Exists
        if (!empty($sink['data']['name'])) {
            $newInstance['name'] = $sink['data']['name'];
        }

        // TODO: Adapt it to the new validation rules i.e. accept either location or location_id ONLY
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
            $newInstance['location_id'] = $input['location_id'];
        }

        $instance = Instance::create($newInstance);
        $instance->teams()->attach(Auth::user()->currentTeam);

        return $instance;
    }
}
