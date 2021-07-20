<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\EmbersPermissionable;
use App\HasEmbersProperties;
use App\Models\Instance;
use App\Rules\Property;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UpdateSink implements UpdatesSinks
{
    use EmbersPermissionable;
    use HasEmbersProperties;

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

        $validated = $this->validate($input, $sink);

        $sink = $this->save($sink, $validated);

        return $sink;
    }

    /**
     * Validate the create Sink operation.
     *
     * @param  array  $input
     * @param  Instance  $sink
     * @return array
     */
    protected function validate(array $input, Instance $sink)
    {
        $validator = Validator::make($input, [
            'sink' => ['filled', 'array:data'],
            'sink.data.*' => [new Property],
            'template_id' => ['filled', 'numeric', 'integer', 'exists:templates,id'],
            'location_id' => ['filled', 'numeric', 'integer', 'exists:locations,id'],
        ]);

        $validated = $validator->validate();

        $this->checkIfPropertiesBelongToTemplate($validated, $sink);

        return $validated;
    }

    /**
     * Update the Sink in the DB.
     *
     * @param  Instance  $sink
     * @param  array  $input
     * @return Instance
     */
    protected function save(Instance $sink, array $input)
    {
        $name = Arr::get($input, 'sink.data.name');

        if (!is_null($name)) $sink->name = $name;

        $data = Arr::get($input, 'sink.data');

        if (!is_null($data)) $sink->values = $data;

        $sink->update($input);

        return $sink;
    }
}
