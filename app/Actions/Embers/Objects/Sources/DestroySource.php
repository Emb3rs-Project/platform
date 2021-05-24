<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Models\Instance;
use Illuminate\Support\Facades\Gate;

class DestroySource implements DestroysSources
{
    /**
     * Find and delete an existing Source.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy(int $id)
    {
        $source = Instance::findOrFail($id);

        Gate::authorize('delete', $source);

        Instance::destroy($source->id);
    }
}
