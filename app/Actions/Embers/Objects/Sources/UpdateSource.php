<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\UpdatesSources;
use App\EmbersPermissionable;
use App\HasEmbersProperties;
use App\Models\Instance;
use App\Models\Location;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UpdateSource implements UpdatesSources
{
    use EmbersPermissionable, HasEmbersProperties;

    /**
     * Validate and update an existing instance.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @param  array  $input
     * @return \App\Models\Instance
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($user, int $id, array $input): Instance
    {
        $this->authorize($user);

        $source = Instance::findOrFail($id);

        //$this->validate($input, $source);

        $source = $this->save($source, $input);

        return $source;
    }

    /**
     * Validate the create Source operation.
     *
     * @param  array  $input
     * @param  \App\Models\Instance  $source
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input, Instance $source): array
    {
        $validator = Validator::make($input, [
            'source' => ['present', 'array:data'],
            'equipment' => ['present', 'array'],
            'equipment.*.id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'equipment.*.category_id' => ['required', 'integer', 'numeric', 'exists:categories,id'],
            'equipment.*.data' => ['required', 'array'],
            'processes' => ['present', 'array'],
            'processes.*.id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'processes.*.category_id' => ['required', 'integer', 'numeric', 'exists:categories,id'],
            'processes.*.data' => ['required', 'array'],
            'template_id' => ['filled', 'integer', 'numeric', 'exists:templates,id'],
            'location_id' => ['filled', 'integer', 'exists:locations,id'],
        ]);

        $validated = $validator->validate();

        $this->checkIfPropertiesAreValid($validated, Arr::get($validated, 'template_id') ?? $source->template_id);

        return $validated;
    }

    /**
     * Save the Source in the DB.
     *
     * @param  \App\Models\Instance $source
     * @param  array  $input
     * @return \App\Models\Instance
     */
    protected function save(Instance $source, array $input): Instance
    {
        $name = Arr::get($input, 'source.data.name');

        if (!is_null($name)) $source->name = $name;

        $values = [
            'properties' => Arr::get($input, 'source.data'),
            'equipment' => Arr::get($input, 'equipment'),
            'processes' => Arr::get($input, 'processes'),
        ];

        if (!is_null(Arr::get($input, 'location'))) {
            $location = Location::create([
                'name' => $source->name,
                'type' => 'point',
                'data' => [
                    "center" => [
                        Arr::get($input, 'location.lat'),
                        Arr::get($input, 'location.lng')
                    ]
                ]
            ]);

            $input['location_id'] = $location->id;
        }

        $source->values = $values;

        $source->update($input);

        return $source;
    }
}
