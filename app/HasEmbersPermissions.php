<?php

namespace App;

use Illuminate\Support\Str;

trait HasEmbersPermissions
{
    /**
     * Get all the available permissions.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        $composer = require base_path('/vendor/autoload.php');

        $classes = array_keys($composer->getClassMap());

        $permissions = [];

        foreach ($classes as $class) {
            if (! Str::startsWith($class, 'App\\Actions\\')) {
                continue;
            }

            $traits = class_uses_recursive($class);

            if (! in_array(EmbersPermissionable::class, $traits)) {
                continue;
            }

            $friendlyActionName = app($class)->getFriendlyActionName();

            array_push($permissions, $friendlyActionName);
        }

        return $permissions;
    }

    /**
     * Get all the available permissions.
     *
     * @return array
     */
    public function getPermissionNamespaces(): array
    {
        $composer = require base_path('/vendor/autoload.php');

        $classes = array_keys($composer->getClassMap());

        $permissionNamespaces = [];

        foreach ($classes as $class) {
            if (! Str::startsWith($class, 'App\\Actions\\')) {
                continue;
            }

            $traits = class_uses_recursive($class);

            if (! in_array(EmbersPermissionable::class, $traits)) {
                continue;
            }

            $actionName = app($class)->getActionName();

            array_push($permissions, $actionName);
        }

        return $permissionNamespaces;
    }
}
