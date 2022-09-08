<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Integration\CharacterizesInstances;
use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\EmbersPermissionable;
use App\HasEmbersProperties;
use App\Models\Instance;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UpdateSink implements UpdatesSinks
{
    use EmbersPermissionable;
    use HasEmbersProperties;

    /**
     * Validate and update an existing Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @param  array  $input
     * @return \App\Models\Instance
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(User $user, int $id, array $input): Instance
    {
        $this->authorize($user);

        $sink = Instance::query()->findOrFail($id);

        //$validated = $this->validate($input, $sink);

        $sink = $this->save($sink, $input);

        return $sink;
    }

    /**
     * Validate the create Sink operation.
     *
     * @param  array  $input
     * @param  \App\Models\Instance  $sink
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input, Instance $sink): array
    {
        $validator = Validator::make($input, [
            'sink' => ['filled', 'array:data'],
            'template_id' => ['filled', 'numeric', 'integer', 'exists:templates,id'],
            'location_id' => ['filled', 'numeric', 'integer', 'exists:locations,id'],
        ]);

        $validated = $validator->validate();

        $this->checkIfPropertiesAreValid($validated, $sink->template_id);

        return $validated;
    }

    /**
     * Update the Sink in the DB.
     *
     * @param  \App\Models\Instance  $sink
     * @param  array  $input
     * @return \App\Models\Instance
     */
    protected function save(Instance $sink, array $input): Instance
    {
        $oldSink = clone $sink;
        $name = Arr::get($input, 'sink.data.name');

        if (!is_null($name)) $sink->name = $name;

        $data = Arr::get($input, 'sink.data');

        if (!is_null($data)) $sink->values = $data;

        if (!is_null(Arr::get($input, 'location'))) {
            // A new location was selected to be used for this Sink
            $location = Location::create([
                'name' => $sink->name,
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

        $sink->update($input);

        app(CharacterizesInstances::class)->characterize($sink, $oldSink->getOriginal());

        return $sink;
    }
}
