<?php

namespace App\Actions\Embers\Objects\Links;

use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\EmbersPermissionable;
use App\Models\Link;
use App\Models\User;

class DestroyLink implements DestroysLinks
{
    use EmbersPermissionable;

    /**
     * Delete an existing Link.
     *
     * @param  \App\Models\User  $user
     * @param  int  $id
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function destroy(User $user, int $id): void
    {
        $this->authorize($user);

        $link = Link::query()->findOrFail($id);

        Link::destroy($link->id);
    }
}
