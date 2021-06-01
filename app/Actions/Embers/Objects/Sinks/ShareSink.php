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
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        Gate::authorize('view', $sink);
        // TODO: also check for sharing permissions

        // TODO: generate a sharing link

        return $sink;
    }
}
