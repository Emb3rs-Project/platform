<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\UpdatesSources;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UpdateSource implements UpdatesSources
{
    /**
     * Validate and update an existing instance.
     *
     * @param  mixed  $user
     * @param  int  $sink
     * @param  array  $input
     * @return Instance
     */
    public function update($user, int $id, array $input)
    {
        // abort_unless($user->hasTeamPermission($user->currentTeam, 'update-source'), 401);

        $source = Instance::findOrFail($id);

        $this->validate($input);

        $source = $this->save($source, $input);

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
            'source.name' => ['filled', 'string', 'max:255'],
            // 'equipments' => ['filled', 'array'],
            'equipments.*.key' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            // 'processes' => ['filled', 'array'],
            'processes.*.key' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'template_id' => ['filled', 'integer', 'numeric', 'exists:templates,id'],
            // // 'location_id' => ['required_without:location' ,'string', 'exists:locations,id'],
            // // 'location' => ['required_without:location_id', 'array', 'exists:locations,id'],
            'location_id' => ['filled'], // for now, later remove current line and uncomment 2 above
        ])->validate();
    }

    /**
     * Save the Source in the DB.
     *
     * @param  Instance $source
     * @param  array    $input
     * @return Instance
     */
    protected function save(Instance $source, array $input)
    {
        if (!empty($input['source']['name'])) {
            $source->name = $input['source']['name'];
        }

        if (!empty($input['equipments'])) {
            foreach ($input['equipments'] as $key => $value) {
                unset($input['equipments'][$key]['template']);
            }

            $source["values"] = [
                "equipments" => $input['equipments'],
                "info" => $source
            ];
        }

        if (!empty($input['equipments'])) {
            foreach ($input['equipments'] as $key => $value) {
                unset($input['equipments'][$key]['template']);
            }

            $source["values"] = [
                "equipments" => $input['equipments'],
                "info" => $source
            ];
        }

        if (!empty($input['location_id'])) {
            if (Arr::accessible($input["location_id"])) {
                $marker = $input["location_id"];
                $location = Location::create([
                    'name' => $source->name,
                    'type' => 'point',
                    'data' => [
                        "center" => [$marker["lat"], $marker["lng"]]
                    ]
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
}
