<?php

namespace App;

use App\Models\Permission;
use Illuminate\Support\Collection;

trait HasEmbersPermissions
{
    /**
    * All the friendly permission names available.
    *
    * @return Illuminate\Support\Collection
    */
    public function getFriendlyPermissionNames(): Collection
    {
        return Permission::all()->pluck('friendly_name');
    }
}
