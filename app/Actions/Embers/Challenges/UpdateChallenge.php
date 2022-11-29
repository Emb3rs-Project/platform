<?php

namespace App\Actions\Embers\Challenges;

use App\Contracts\Embers\Challenges\UpdatesChallenges;
use App\EmbersPermissionable;
use App\Models\Challenge;
use App\Models\User;
use Validator;

class UpdateChallenge implements UpdatesChallenges
{
    use EmbersPermissionable;

    public function update(User $user, int $id, array $input): Challenge
    {
        $this->authorize($user);

        $challenge = Challenge::findOrFail($id);

        $validated = $this->validate($input);

        $challenge = $this->save($challenge, $validated);

        return $challenge;
    }

    /**
     * Validate the create Challenge operation.
     */
    protected function validate(array $input): array
    {
        $validator = Validator::make($input, [
            'name' => ['filled', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'goal_id' => ['required', 'numeric'],
        ]);

        return $validator->validate();
    }

    /**
     * Save the Challenge in the DB.
     */
    protected function save(Challenge $challenge, array $validated): Challenge
    {
        if (!empty($validated['name'])) {
            $challenge->name = $validated['name'];
        }

        if (!empty($validated['description'])) {
            $challenge->description = $validated['description'];
        }
        if (!empty($validated['goal_id'])) {
            $challenge->goal_id = $validated['goal_id'];
        }

        $challenge->save();

        return $challenge;
    }
}
