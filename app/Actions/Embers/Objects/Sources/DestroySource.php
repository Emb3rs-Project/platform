<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\EmbersPermissionable;
use App\Models\Instance;

class DestroySource implements DestroysSources
{
    use EmbersPermissionable;

    /**
     * Find and delete an existing Source.
     *
     * @param  mixed  $user
     * @param  int  $id
     * @return void
     */
    public function destroy($user, int $id)
    {
        $this->authorize($user);

        $source = Instance::findOrFail($id);

        Instance::destroy($source->id);
    }
}
