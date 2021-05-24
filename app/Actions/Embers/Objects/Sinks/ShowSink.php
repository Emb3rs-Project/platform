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
    * @param int  $id
    * @return mixed
    */
    public function show(int $id)
    {
        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        Gate::authorize('view', $sink);

        return $sink;
    }
}