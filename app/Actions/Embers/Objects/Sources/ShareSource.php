<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\SharesSources;
use App\EmbersPermissionable;
use App\Models\Instance;
use App\Models\User;

class ShareSource implements SharesSources
{
    use EmbersPermissionable;

    /**
     * Share a given Source.
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

        $source = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        // TODO: generate a sharing link

        return $source;
    }
}
