<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\EmbersPermissionable;
use App\Models\Instance;

class DestroySink implements DestroysSinks
{
    use EmbersPermissionable;

    /**
     * Find and delete an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id)
    {
        $this->authorize($user);

        $sink = Instance::findOrFail($id);

        Instance::destroy($sink->id);
    }
}
