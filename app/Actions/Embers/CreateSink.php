<?php

namespace App\Actions\Embers;

use App\Contracts\Embers\Objects\CreatesSinks;
use App\Models\GeoObject;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CreateSink implements CreatesSinks
{
    /**
     * Validate and create a new instance.
     *
     * @param  mixed $user
     * @param  array $input
     * @return mixed
     */
    public function create($user, $input)
    {
        Gate::authorize('create', Instance::class);

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
            'sink.data.name' => ['filled', 'string', 'max:255'],
            // 'equipments' => ['present'], // Will change later
            'template_id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            // 'location_id' => ['required_without:location' ,'string', 'exists:locations,id'],
            // 'location' => ['required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['required'], // for now, later remove current line and uncomment 2 above
        ])
        ->validate();
    }

    /**
     * Save the Sink in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function save(mixed $user, array $input): Instance
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
        if (isset($sink['data']['name'])) {
            $newInstance['name'] = $sink['data']['name'];
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

    // @geocfu: Seems too verbose for now, leave it for future use (maaaayyyyyybe)
    // /**
    //  * Get the validation rules for adding a team member.
    //  *
    //  * @return array
    //  */
    // protected function rules()
    // {
    //     return array_filter([
    //         'sink.data.name' => ['string', 'max:255'],
    //         'equipments' => ['present', 'array'], // Will change later
    //         'template_id' => ['required', 'string', 'exists:templates,id'],
    //         'location_id' => ['required_without:location' ,'string', 'exists:locations,id'],
    //         'location' => ['required_without:location', 'string', 'exists:locations,id'],
    //     ]);
    // }
}
