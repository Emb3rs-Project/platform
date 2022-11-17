<?php

namespace App\Actions\Embers\Challenges;

use App\Contracts\Embers\Challenges\StoresChallenges;
use App\EmbersPermissionable;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class StoreChallenge implements StoresChallenges
{
    use EmbersPermissionable;

    /**
     * Validate and create a new Link.
     */
    public function store(User $user, array $input): Challenge
    {
        $this->authorize($user);

        $validated = $this->validate($input);

        $link = $this->save($user, $validated);

        return $link;
    }

    /**
     * Validate the create Project operation.
     */
    protected function validate(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'goal_id' => ['nullable', 'numeric'],
        ]);

        return $validator->validate();
    }

    /**
     * Save the Project in the DB.
     */
    protected function save(User $user, array $validated): Challenge
    {
        $challenge = Challenge::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'goal_id' => $validated['goal_id'] ?? null,
        ]);


        return $challenge;
    }
}
