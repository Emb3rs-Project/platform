<?php

namespace App\Actions\Embers;

use App\Contracts\Embers\Objects\CreatesSinks;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CreateSink implements CreatesSinks
{
    /**
     * Validate and create a new sink.
     *
     * @param  mixed  $sink
     * @return mixed
     */
    public function create($user, $sink)
    {
        Gate::forUser($user)->authorize('create-sink', $sink);
        // $newInstance = [
        //     "name" => 'Not Defined',
        //     "values" => [
        //         "equipments" => $equipments
        //     ],
        //     "template_id" => $request->get('template_id'),
        //     "location_id" => null
        // ];

        Validator::make($sink, [
            'name' => ['required', 'string', 'max:255'],
            'values.equipments' => ['required', 'array'],
            'template_id' => ['required', 'string', 'exists:template,id'],
            'location_id' => ['required', 'string', 'exists:location,id'],
        ])->validateWithBag('createSink');

        // AddingTeam::dispatch($user);

        // $user->switchTeam($team = $user->ownedTeams()->create([
        //     'name' => $input['name'],
        //     'personal_team' => false,
        // ]));

        // return $team;
    }
}
