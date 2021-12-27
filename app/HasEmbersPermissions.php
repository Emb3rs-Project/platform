<?php

namespace App;

use App\Models\Permission;
use Illuminate\Support\Collection;

trait HasEmbersPermissions
{
    /**
     * All permissions names with their corresponding group available.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFriendlyPermissions(): Collection
    {
        return Permission::query()->get()->map(function ($permission) {
            return [
                'id' => $permission->friendly_id,
                'name' => $permission->friendly_name,
                'group' => $permission->group
            ];
        });
    }
}
