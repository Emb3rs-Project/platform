<?php

namespace App\Actions\Embers\MapData;

use App\Contracts\Embers\MapData\StoresMapData;
use App\Models\User;
use App\Rules\Coordinates;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class StoreMapData implements StoresMapData
{
    /**
     * Validate and create new map data.
     *
     * @param  \App\Models\User  $user
     * @param  array  $input
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(User $user, array $input): array
    {
        $validated = $this->validate($input);

        $data = $this->save($user, $validated);

        return $data;
    }

    /**
     * Validate the create map data operation.
     *
     * @param  array  $input
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validate(array $input): array
    {
        $validator = Validator::make($input, [
            'map.center.lat' => ['filled', 'numeric', new Coordinates],
            'map.center.lng' => ['filled', 'numeric', new Coordinates],
            'map.zoom' => ['filled', 'numeric', 'min:0', 'max:18'],
            'map.defaultLocation.lat' => ['filled', 'numeric', new Coordinates],
            'map.defaultLocation.lng' => ['filled', 'numeric', new Coordinates],
        ]);

        return $validator->validated();
    }

    /**
     * Save the map data in the DB.
     *
     * @param  \App\Models\User  $user
     * @param  array  $validated
     * @return array
     */
    protected function save(User $user, array $validated): array
    {
        $data = $user->data;

        Arr::add($data, 'map', []);

        if (Arr::has($validated, 'map.center.lat') && Arr::has($validated, 'map.center.lng')) {
            $center = [
                'lat' => Arr::get($validated, 'map.center.lat'),
                'lng' => Arr::get($validated, 'map.center.lng'),
            ];

            Arr::set($data, 'map.center', $center);
        };

        if (Arr::has($validated, 'map.zoom')) {
            $zoom =  Arr::get($validated, 'map.zoom');

            Arr::set($data, 'map.zoom', $zoom);
        }

        if (Arr::has($validated, 'map.defaultLocation.lat') && Arr::has($validated, 'map.defaultLocation.lng')) {
            $defaultLocation = [
                'lat' => Arr::get($validated, 'map.defaultLocation.lat'),
                'lng' => Arr::get($validated, 'map.defaultLocation.lng'),
            ];

            Arr::set($data, 'map.defaultLocation', $defaultLocation);
        };

        $user->data = $data;

        $user->save();

        return $data;
    }
}
