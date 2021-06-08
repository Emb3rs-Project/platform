<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\SharesSources;
use App\EmbersPermissionable;
use App\Models\Instance;

class ShareSource implements SharesSources
{
    use EmbersPermissionable;

    /**
     * Find and return an existing Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        $this->authorize($user);

        $source = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        // TODO: generate a sharing link

        return $source;
    }
}
