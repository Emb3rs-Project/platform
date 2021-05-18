<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\Models\Instance;
use Illuminate\Support\Facades\Gate;

class ShowSink implements ShowsSinks
{
    /**
    * Find and return an existing Sink.
    *
    * @param mixed  $user
    * @param int    $id
    * @return mixed
    */
    public function show(mixed $user, int $id)
    {
        $sink = Instance::with([
            'location',
            'template',
            'template.category',
            'location.geoObject'
        ])->findOrFail($id);

        Gate::authorize('view', $sink);

        return $sink;
    }

    public function redirectTo()
    {
    }
}
