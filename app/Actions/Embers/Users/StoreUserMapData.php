<?php

namespace App\Actions\Embers\Users;

use App\Contracts\Embers\Users\StoresUsersMapData;
use App\Models\User;
use App\Rules\Coordinates;
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
            'map.center.lat' => ['required', 'numeric', new Coordinates],
            'map.center.lng' => ['required', 'numeric', new Coordinates],
            // 'map.zoom' => ['required', 'numeric', 'min:0', 'max:18']
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
        $data = [
            'map' => [
                'center' => [
                    'lat' => $input['map']['center']['lat'],
                    'lng' => $input['map']['center']['lng']
                ],
                // 'zoom' => $input['map']['zoom']
            ]
        ];

        $user = User::whereId($userId)->first();

        $user->data = $data;

        $user->save();

        return $data;
    }
}
