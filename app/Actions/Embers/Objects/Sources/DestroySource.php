<?php

namespace App\Actions\Embers\Objects\Sources;

use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Models\Instance;
use Illuminate\Support\Facades\Gate;

class DestroySource implements DestroysSources
{
    /**
     * Find and delete an existing Sink.
     *
     * @param  mixed $user
     * @param  string $id
     * @return void
     */
    public function destroy($user, string $id)
    {
        $source = Instance::findOrFail($id);

        Gate::authorize('delete', $source);

        Instance::destroy($source->id);
    }

    public function redirectTo()
    {
    }
}
