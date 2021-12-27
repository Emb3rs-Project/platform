<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\EmbersPermissionable;
use App\Models\Instance;

class DestroySink implements DestroysSinks
{
    use EmbersPermissionable;

    /**
     * Delete an existing Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy($user, int $id): void
    {
        $this->authorize($user);

        $sink = Instance::query()->findOrFail($id);

        // Instance::destroy($sink->id);
        $sink->delete();
    }
}
