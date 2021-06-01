<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;

class CreateLink implements CreatesLinks
{
    /**
     * Display the necessary objects for the creation of a Link.
     *
     * @param  mixed  $user
     * @return mixed
     */
    public function create($user)
    {
        Gate::authorize('create', Link::class);

        return [];
    }
}
