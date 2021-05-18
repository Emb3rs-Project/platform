<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\Models\Instance;
use Illuminate\Support\Facades\Gate;

class DestroySink implements DestroysSinks
{
    /**
     * Find and delete an existing Sink.
     *
     * @param  mixed $user
     * @param  string $id
     * @param  array $input
     * @return mixed
     */
    public function destroy($user, string $id)
    {
        $sink = Instance::findOrFail($id);

        Gate::authorize('delete', $sink);

        Instance::destroy($sink->id);
    }

    public function redirectTo()
    {
    }
}
