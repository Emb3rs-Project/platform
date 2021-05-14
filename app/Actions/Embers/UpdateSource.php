<?php

namespace App\Actions\Embers;

use App\Contracts\Embers\Objects\UpdatesSources;
use App\Models\GeoObject;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UpdateSource implements UpdatesSources
{
    /**
     * Validate and update an existing instance.
     *
     * @param  mixed $user
     * @param  Instance $sink
     * @param  array $input
     * @return Instance
     */
    public function update(mixed $user, Instance $source, array $input)
    {
        Gate::authorize('update', $source);

        $this->validate($input);

        $source = $this->save($user, $source, $input);

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
            'equipments' => ['filled', 'array'],
            'equipments.*.id' => ['required', 'string', 'exists:categories,id'],
            'processes' => ['filled', 'array'],
            'template_id' => ['filled', 'integer','numeric', 'exists:templates,id'],
            // 'location_id' => ['filled', 'required_without:location' ,'string', 'exists:locations,id'],
            // 'location' => ['filled', 'required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['filled'], // for now. Later remove current line and uncomment 2 above
        ])
        ->validate();
    }

    protected function save(mixed $user, Instance $source, array $input): Instance
    {
        // TODO: update processes

        if (!empty($input['equipments'])) {
            foreach ($input['equipments'] as $key => $value) {
                unset($input['equipments'][$key]['template']);
            }

            $source["values"] = [
                "equipments" => $input['equipments'],
                "info" => $source
            ];
        }

        if (!empty($input['source']['data']['name'])) {
            $source->name = $input['source']['data']['name'];
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
                    'name' => $source->name,
                    'geo_object_id' => $geo->id
                ]);
                $source->location_id = $location->id;
            } else {
                $source['location_id'] = $input['location_id'];
            }
        }

        if (!empty($input['template_id'])) {
            $source->template_id = $input['template_id'];
        }

        $source->save();

        return $source;
    }

    public function redirectTo()
    {
        //TODO
    }
}
