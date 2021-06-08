<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\EmbersPermissionable;
use App\Models\Instance;

class ShowSink implements ShowsSinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function show($user, int $id)
    {
        $this->authorize($user);

        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        return $sink;
    }
}
