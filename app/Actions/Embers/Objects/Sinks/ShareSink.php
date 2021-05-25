<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\SharesSinks;
use App\Models\Instance;
use Illuminate\Support\Facades\Gate;

class ShareSink implements SharesSinks
{
    /**
    * Find and return an existing Sink.
    *
    * @param  int  $id
    * @return mixed
    */
    public function share(int $id)
    {
        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        Gate::authorize('view', $sink);
        // TODO: also check for sharing permissions

        return $sink;
    }
}
