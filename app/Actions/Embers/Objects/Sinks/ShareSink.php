<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\SharesSinks;
use App\EmbersPermissionable;
use App\Models\Instance;

class ShareSink implements SharesSinks
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Sink.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        $this->authorize($user);

        $sink = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        // TODO: generate a sharing link

        return $sink;
    }
}
