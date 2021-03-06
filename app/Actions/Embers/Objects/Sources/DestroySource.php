<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\User;

class DestroySource implements DestroysSources
{
    use EmbersPermissionable;

    /**
     * Find and delete an existing Source.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(User $user, int $id): Instance
    {
        $this->authorize($user);

        $source = Instance::query()->findOrFail($id);

        // Instance::destroy($source->id);
        $source->delete();

        return $source;
    }
}
