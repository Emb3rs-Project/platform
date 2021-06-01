<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\SharesSources;
use App\Models\Instance;
use Illuminate\Support\Facades\Gate;

class ShareSource implements SharesSources
{
    /**
     * Find and return an existing Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return mixed
     */
    public function share($user, int $id)
    {
        $source = Instance::with(['location', 'template', 'template.category'])->findOrFail($id);

        Gate::authorize('view', $source);
        // TODO: also check for sharing permissions

        // TODO: generate a sharing link

        return $source;
    }
}
