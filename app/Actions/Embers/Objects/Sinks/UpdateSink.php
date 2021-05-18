<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\Models\GeoObject;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UpdateSink implements UpdatesSinks
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
        $sink = Instance::findOrFail($id);

        Gate::authorize('update', $sink);

        $this->validate($input);

        $sink = $this->save($user, $sink, $input);

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
            'template_id' => ['filled', 'integer','numeric', 'exists:templates,id'],
            // 'location_id' => ['filled', 'required_without:location' ,'string', 'exists:locations,id'],
            // 'location' => ['filled', 'required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['filled'], // for now. Later remove current line and uncomment 2 above
        ])
        ->validate();
    }

    protected function save(mixed $user, Instance $sink, array $input): Instance
    {
        // TODO: attach the user id to the entity

        if (!empty($input['sink']['data']['name'])) {
            $sink->name = $input['sink']['data']['name'];
        }

        if (!empty($input['location_id'])) {
            if (Arr::accessible($input["location_id"])) {
                $marker = $input["location_id"];
                $geo = GeoObject::create([
                    'type' => 'point',
                    'data' => [
                        "center" => [$marker["lat"], $marker["lng"]]
                    ]
                ]);

                $location = Location::create([
                    'name' => $sink->name,
                    'geo_object_id' => $geo->id
                ]);
                $sink->location_id = $location->id;
            } else {
                $sink['location_id'] = $input['location_id'];
            }
        }

        if (!empty($input['template_id'])) {
            $sink->template_id = $input['template_id'];
        }

        $sink->save();

        return $sink;
    }

    public function redirectTo()
    {
        //TODO
    }
}
