<?php

namespace App\Actions\Embers\Users;

use App\Contracts\Embers\Users\StoresUsersMapData;
use App\Models\User;
use App\Rules\Coordinates;
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

        $data = $this->save($user, $input);

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
            'map' => ['required', 'array'],
            'center.*' => ['required', 'numeric', new Coordinates]
        ])->validate();
    }

    /**
     * Save the map data in the DB.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return Instance
     */
    protected function save($user, array $input)
    {
        $data = [
            'map' => [
                'center' => [
                    'lat' => $input['map']['center']['lat'],
                    'lng' => $input['map']['center']['lng']
                ]
            ]
        ];

        $dbUser = User::whereId($user->id)->first();

        $dbUser->data = $data;

        $dbUser->save();

        return $data;
    }
}
