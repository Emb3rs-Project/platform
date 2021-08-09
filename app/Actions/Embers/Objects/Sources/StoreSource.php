<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\EmbersPermissionable;
use App\HasEmbersProperties;
use App\Helpers\Nova\Action\DispatchCustomAction;
use App\Models\Instance;
use App\Models\Location;
use App\Nova\Actions\InstanceProcessing;
use App\Rules\Coordinates;
use App\Rules\Prohibit;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\ActionFields;

class StoreSource implements StoresSources
{
    use EmbersPermissionable, HasEmbersProperties;

    /**
     * Validate and create a new instance.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Instance
     */
    public function store($user, array $input)
    {
        $this->authorize($user);

        $validated = $this->validate($input);

        $source = $this->save($user, $validated);

        return $source;
    }

    /**
     * Validate the create Source operation.
     *
     * @param  array  $input
     * @return array
     */
    protected function validate(array $input)
    {
        $validator = Validator::make($input, [
            'source' => ['required', 'array:data'],
            //equipments
            'equipments' => ['nullable', 'array'],
            'equipments.*.key' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'equipments.*.parent' => ['required', 'integer', 'numeric', 'exists:categories,id'],
            'equipments.*.data' => ['required', 'array'],
            //processes
            'processes' => ['nullable', 'array'],
            'processes.*.key' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'processes.*.parent' => ['required', 'integer', 'numeric', 'exists:categories,id'],
            'processes.*.data' => ['required', 'array'],
            //template
            'template_id' => ['required', 'integer', 'numeric', 'exists:templates,id'],
            'location_id' => [
                Rule::requiredIf(function () use ($input) {
                    return !Arr::has($input, 'location') || Arr::get($input, 'location') === null;
                }),
                new Prohibit($input, 'location'),
                'nullable',
                'numeric',
                'integer',
                'exists:locations,id'
            ],
            'location' => [
                Rule::requiredIf(function () use ($input) {
                    return !Arr::has($input, 'location_id') || Arr::get($input, 'location_id') === null;
                }),
                new Prohibit($input, 'location_id'),
                'nullable',
                'array:lat,lng',
            ],
            'location.lat' => ['required_with:location', 'numeric', new Coordinates],
            'location.lng' => ['required_with:location', 'numeric', new Coordinates],
        ]);

        $validated = $validator->validate();

        $this->checkIfPropertiesAreValid($validated);

        $this->checkIfEquipmentPropertiesAreValid($validated);

        $this->checkIfProcessPropertiesAreValid($validated);

        return $validated;
    }

    /**
     * Save the Source in the DB.
     *
     * @param  mixed  $user
     * @param  array  $validated
     * @return Instance
     */
    protected function save($user, array $validated)
    {
        info('to save');


        $newInstance = [
            'name' => Arr::get($validated, 'source.data.name') ?? 'Not Defined',
            'values' => Arr::get($validated, 'source.data'),
            'equipments' => Arr::get($validated, 'equipments'),
            'processes' => Arr::get($validated, 'source.data'),
            'scripts' => [],
            'template_id' => Arr::get($validated, 'template_id'),
            'location_id' => Arr::get($validated, 'location_id')
        ];

        if (!is_null(Arr::get($validated, 'location'))) {
            // A new location was selected to be used for this Sink
            $location = Location::create([
                'name' => Arr::get($newInstance, 'name'),
                'type' => 'point',
                'data' => [
                    "center" => [
                        Arr::get($validated, 'location.lat'),
                        Arr::get($validated, 'location.lng')
                    ]
                ]
            ]);

            Arr::set($newInstance, 'location_id', $location->id);
        }

        // $source = $input['source'];
        // $equipments = $input['equipments'];
        // foreach ($equipments as $key => $value) {
        //     unset($equipments[$key]['template']);
        // }

        // $processes = $input['processes'];
        // foreach ($processes as $key => $value) {
        //     unset($processes[$key]['template']);
        // }

        // $newInstance = [
        //     "name" => 'Not Defined',
        //     "values" => [
        //         "equipments" => $equipments,
        //         "processes" => $processes,
        //         "info" => $source
        //     ],
        //     "template_id" => $input['template_id'],
        //     "location_id" => null
        // ];

        // Check if Property Name Exists
        // if (!empty($source['name'])) {
        //     $newInstance['name'] = $source['name'];
        // }

        // if (is_array($input["location_id"])) {
        //     $marker = $input["location_id"];
        //     $location = Location::create([
        //         'name' => $newInstance['name'],
        //         'type' => 'point',
        //         'data' => [
        //             "center" => [$marker["lat"], $marker["lng"]]
        //         ]
        //     ]);
        //     $newInstance['location_id'] = $location->id;
        // } else {
        //     // Check if Location is Set
        //     $newInstance['location_id'] = $input['location_id'];
        // }

        // $instance = Instance::create($newInstance);
        // $instance->teams()->attach($user->currentTeam);


        // /**
        //  * STUFF
        //  */
        // $action = new InstanceProcessing();
        // $user_id = $user->id;


        // DispatchCustomAction::dispatchAction(
        //     $action,
        //     new ActionFields(new Collection(), new Collection()),
        //     [$instance],
        //     $user_id
        // );

        // return $instance;
    }
}
