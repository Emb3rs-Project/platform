<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\Models\Instance;
use Illuminate\Support\Facades\Gate;

class DestroySink implements DestroysSinks
{
    /**
     * Find and delete an existing Link.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @param  array  $input
     * @return void
     */
    public function destroy($user, int $id)
    {
        $sink = Instance::findOrFail($id);

        Gate::authorize('delete', $sink);

        Instance::destroy($sink->id);
    }
}
