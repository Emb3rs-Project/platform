<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UpdateSink implements UpdatesSinks
{
    use EmbersPermissionable;

    /**
     * Validate, update and return an existing instance.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return Instance
     */
    public function update($user, int $id, array $input)
    {
        $this->authorize($user);

        $sink = Instance::findOrFail($id);

        $this->validate($input);

        $sink = $this->save($sink, $input);

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
            'sink' => ['filled', 'array'],
            'sink.data.name' => ['filled', 'string', 'max:255'],
            'equipments' => ['filled', 'array'],
            'equipments.*.key' => ['required', 'string', 'exists:instances,id'],
            'template_id' => ['filled', 'integer', 'numeric', 'exists:templates,id'],
            // 'location_id' => ['filled', 'required_without:location' ,'string', 'exists:locations,id'],
            // 'location' => ['filled', 'required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['filled'], // for now. Later remove current line and uncomment 2 above
        ])->validate();
    }

    /**
     * Save the Sink in the DB.
     *
     * @param  Instance $sink
     * @param  array    $input
     * @return Instance
     */
    protected function save(Instance $sink, array $input)
    {
        if (!empty($input['sink']['data']['name'])) {
            $sink->name = $input['sink']['data']['name'];
        }

        if (!empty($input['equipments'])) {
            $newInstance['name']['equipments'] = $input['equipments'];
        }

        if (!empty($input['template_id'])) {
            $sink->template_id = $input['template_id'];
        }

        if (!empty($input['location_id'])) {
            if (Arr::accessible($input["location_id"])) {
                $marker = $input["location_id"];
                $location = Location::create([
                    'name' => $sink->name,
                    'type' => 'point',
                    'data' => [
                        "center" => [$marker["lat"], $marker["lng"]]
                    ]
                ]);
                $sink->location_id = $location->id;
            } else {
                $sink['location_id'] = $input['location_id'];
            }
        }

        $sink->save();

        return $sink;
    }
}
