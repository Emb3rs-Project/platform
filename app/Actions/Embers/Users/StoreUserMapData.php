<?php

namespace App\Actions\Embers\Users;

use App\Contracts\Embers\Users\StoresUsersMapData;
use App\Models\User;
use App\Rules\Coordinates;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class StoreUserMapData implements StoresUsersMapData
{
    /**
     * Validate and create new map data for the currently logged in user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function store($user, array $input)
    {
        $this->validate($input);

        $data = $this->save($user->id, $input);

        return $data;
    }

    /**
     * Validate the create user map data operation.
     *
     * @param  array  $input
     * @return void
     */
    protected function validate(array $input)
    {
        Validator::make($input, [
            'map.center.lat' => ['filled', 'numeric', new Coordinates],
            'map.center.lng' => ['filled', 'numeric', new Coordinates],
            'map.zoom' => ['filled', 'numeric', 'min:0', 'max:18']
        ])->validate();
    }

    /**
     * Save the map data in the DB.
     *
     * @param  int  $userId
     * @param  array  $input
     * @return Instance
     */
    protected function save(int $userId, array $input)
    {
        $user = User::whereId($userId)->first();

        $data = $user->data;

        Arr::add($data, 'map', []);

        if (Arr::has($input, 'map.center.lat') && Arr::has($input, 'map.center.lng')) {
            $center = [
                'lat' => Arr::get($input, 'map.center.lat'),
                'lng' => Arr::get($input, 'map.center.lng'),
            ];

            Arr::set($data, 'map.center', $center);
        };

        if (Arr::has($input, 'map.zoom')) {
            $zoom =  Arr::get($input, 'map.zoom');

            Arr::set($data, 'map.zoom', $zoom);
        }

        $user->data = $data;

        $user->save();

        return $data;
    }
}
