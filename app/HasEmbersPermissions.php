<?php

namespace App;

use Illuminate\Foundation\Application;
use Illuminate\Support\Str;
use ReflectionClass;

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

            $reflector = new ReflectionClass($class);
            $traits = $reflector->getTraitNames();

            if (! in_array(EmbersPermissionable::class, $traits)) {
                continue;
            }

            $friendlyActionName = Application::getInstance()->make($class)->getFriendlyActionName();

            array_push($permissions, $friendlyActionName);
        }

        return $permissions;
    }
}
