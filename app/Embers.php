<?php

namespace App;

use App\Contracts\Embers\Objects\CreatesSinks;
use App\Contracts\Embers\Objects\CreatesSources;
use App\Contracts\Embers\Objects\UpdatesSinks;
use App\Contracts\Embers\Objects\UpdatesSources;

class Embers
{

    // TODO: define permissions here IF we create them manually


    /**
    * Register a class / callback that should be used to create sinks.
    *
    * @param  string  $class
    * @return void
    */
    public static function createSinksUsing(string $class)
    {
        return app()->singleton(CreatesSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to update sinks.
    *
    * @param  string  $class
    * @return void
    */
    public static function updateSinksUsing(string $class)
    {
        return app()->singleton(UpdatesSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to create sources.
    *
    * @param  string  $class
    * @return void
    */
    public static function createSourcesUsing(string $class)
    {
        return app()->singleton(CreatesSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to update sources.
    *
    * @param  string  $class
    * @return void
    */
    public static function updateSourcesUsing(string $class)
    {
        return app()->singleton(UpdatesSources::class, $class);
    }
}
