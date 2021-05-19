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
     * @return mixed
     */
    public function create()
    {
        Gate::authorize('create', Link::class);

        return [];
    }

    public function redirectTo()
    {
    }
}
