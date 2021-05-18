<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;

class CreateLink implements CreatesLinks
{
    /**
     * Display all the available Sinks.
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
