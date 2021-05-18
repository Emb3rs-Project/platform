<?php

namespace App;

use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Sinks\CreatesSinks;
use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\Contracts\Embers\Objects\Sinks\EditsSinks;
use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\Contracts\Embers\Objects\Sources\UpdatesSources;

class Embers
{
    /**
    * Register a class / callback that should be used to index all Sinks.
    *
    * @param  string  $class
    * @return void
    */
    public static function indexSinksUsing(string $class)
    {
        return app()->singleton(IndexesSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the creation of a Sink.
    *
    * @param  string  $class
    * @return void
    */
    public static function createSinksUsing(string $class)
    {
        return app()->singleton(CreatesSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to display a given Sink.
    *
    * @param  string  $class
    * @return void
    */
    public static function showSinksUsing(string $class)
    {
        return app()->singleton(ShowsSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to create Sinks.
    *
    * @param  string  $class
    * @return void
    */
    public static function storeSinksUsing(string $class)
    {
        return app()->singleton(StoresSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the updating of a given Sink.
    *
    * @param  string  $class
    * @return void
    */
    public static function editSinksUsing(string $class)
    {
        return app()->singleton(EditsSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to update a given Sink.
    *
    * @param  string  $class
    * @return void
    */
    public static function updateSinksUsing(string $class)
    {
        return app()->singleton(UpdatesSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to delete a given Sink.
    *
    * @param  string  $class
    * @return void
    */
    public static function destroySinksUsing(string $class)
    {
        return app()->singleton(DestroysSinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to index all Sources.
    *
    * @param  string  $class
    * @return void
    */
    public static function indexSourcesUsing(string $class)
    {
        return app()->singleton(IndexesSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the creation of a Source.
    *
    * @param  string  $class
    * @return void
    */
    public static function createSourcesUsing(string $class)
    {
        return app()->singleton(CreatesSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to display a given Sink.
    *
    * @param  string  $class
    * @return void
    */
    public static function showSourcesUsing(string $class)
    {
        return app()->singleton(ShowsSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to create sources.
    *
    * @param  string  $class
    * @return void
    */
    public static function storeSourcesUsing(string $class)
    {
        return app()->singleton(StoresSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the updating of a given Sοθρψε.
    *
    * @param  string  $class
    * @return void
    */
    public static function editSourcesUsing(string $class)
    {
        return app()->singleton(EditsSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to update a given Source.
    *
    * @param  string  $class
    * @return void
    */
    public static function updateSourcesUsing(string $class)
    {
        return app()->singleton(UpdatesSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to delete a given Source.
    *
    * @param  string  $class
    * @return void
    */
    public static function destroySourcesUsing(string $class)
    {
        return app()->singleton(DestroysSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to index all Links.
    *
    * @param  string  $class
    * @return void
    */
    public static function indexLinksUsing(string $class)
    {
        return app()->singleton(IndexesLinks::class, $class);
    }
}
