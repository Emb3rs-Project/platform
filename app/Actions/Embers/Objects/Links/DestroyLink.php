<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\Models\Link;
use Illuminate\Support\Facades\Gate;

class DestroyLink implements DestroysLinks
{
    /**
     * Find and delete an existing Link.
     *
     * @param  int   $id
     * @param  array $input
     * @return void
     */
    public function destroy(int $id)
    {
        $link = Link::findOrFail($id);

        Gate::authorize('delete', $link);

        Link::destroy($link->id);
    }
}
