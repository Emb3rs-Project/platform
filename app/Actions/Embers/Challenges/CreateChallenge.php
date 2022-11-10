<?php

namespace App\Actions\Embers\Challenges;

use App\Contracts\Embers\Challenges\CreatesChallenges;
use App\EmbersPermissionable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CreateChallenge implements CreatesChallenges
{
    use EmbersPermissionable;

    /**
     * Display the necessary objects for the creation of a Project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $user
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Location>[]
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function create(User $user): Collection
    {
        $this->authorize($user);

        return new Collection();
    }
}
