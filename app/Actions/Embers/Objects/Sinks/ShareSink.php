<?php

namespace App\Actions\Embers\Objects\Sinks;

use App\Contracts\Embers\Objects\Sinks\SharesSinks;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\User;

class ShareSink implements SharesSinks
{
    use EmbersPermissionable;

    /**
     * Share a given Sink.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return mixed
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function share(User $user, int $id)
    {
        $this->authorize($user);

        $sink = Instance::query()
            ->with(['location', 'template', 'template.category'])
            ->findOrFail($id);

        // TODO: generate a sharing link

        return $sink;
    }
}
