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

    /**
    * All permissions names available.
    *
    * @return Illuminate\Support\Collection
    */
    public function getPermissionsKeyValue(): Collection
    {
        return Permission::all()->map(fn ($p) => ["name" => $p->friendly_name, "group" => $p->group]);
    }
}
