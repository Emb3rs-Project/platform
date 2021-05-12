<?php

namespace App\Actions\Embers;

use App\Contracts\Embers\Objects\CreatesSinks;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CreateSink implements CreatesSinks
{
    /**
     * Validate and create a new instance.
     *
     * @param  mixed $user
     * @return mixed $instance
     */
    public static function create($user, $instance)
    {
        Gate::forUser($user)->authorize('create', $instance);

        Validator::make($instance, [
            'name' => ['required', 'string', 'max:255'],
            'values.equipments' => ['required', 'array'],
            'template_id' => ['required', 'string', 'exists:templates,id'],
            'location_id' => ['required', 'string', 'exists:locations,id'],
        ])->validateWithBag('createSink');



        // AddingTeam::dispatch($user);

        // $user->switchTeam($team = $user->ownedTeams()->create([
        //     'name' => $input['name'],
        //     'personal_team' => false,
        // ]));

        // return $team;
    }
}
