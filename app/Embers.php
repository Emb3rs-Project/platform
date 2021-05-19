<?php

namespace App;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\Contracts\Embers\Objects\Links\UpdatesLinks;
use App\Contracts\Embers\Objects\Projects\CreatesProjects;
use App\Contracts\Embers\Objects\Projects\DestroysProjects;
use App\Contracts\Embers\Objects\Projects\EditsProjects;
use App\Contracts\Embers\Objects\Projects\IndexesProjects;
use App\Contracts\Embers\Objects\Projects\ShowsProjects;
use App\Contracts\Embers\Objects\Projects\StoresProjects;
use App\Contracts\Embers\Objects\Projects\UpdatesProjects;
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
    * Register a class / callback that should be used to display a given Source.
    *
    * @param  string  $class
    * @return void
    */
    public static function showSourcesUsing(string $class)
    {
        return app()->singleton(ShowsSources::class, $class);
    }

    /**
    * Register a class / callback that should be used to create Sources.
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
    * objects for the updating of a given Source.
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

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the creation of a Link.
    *
    * @param  string  $class
    * @return void
    */
    public static function createLinksUsing(string $class)
    {
        return app()->singleton(CreatesLinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to create Links.
    *
    * @param  string  $class
    * @return void
    */
    public static function storeLinksUsing(string $class)
    {
        return app()->singleton(StoresLinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to display a given Link.
    *
    * @param  string  $class
    * @return void
    */
    public static function showLinksUsing(string $class)
    {
        return app()->singleton(ShowsLinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the updating of a given Link.
    *
    * @param  string  $class
    * @return void
    */
    public static function editLinksUsing(string $class)
    {
        return app()->singleton(EditsLinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to update a given Link.
    *
    * @param  string  $class
    * @return void
    */
    public static function updateLinksUsing(string $class)
    {
        return app()->singleton(UpdatesLinks::class, $class);
    }

    /**
    * Register a class / callback that should be used to delete a given Link.
    *
    * @param  string  $class
    * @return void
    */
    public static function destroyLinksUsing(string $class)
    {
        return app()->singleton(DestroysLinks::class, $class);
    }



    /**
    * Register a class / callback that should be used to index all Projects.
    *
    * @param  string  $class
    * @return void
    */
    public static function indexProjectsUsing(string $class)
    {
        return app()->singleton(IndexesProjects::class, $class);
    }

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the creation of a Project.
    *
    * @param  string  $class
    * @return void
    */
    public static function createProjectsUsing(string $class)
    {
        return app()->singleton(CreatesProjects::class, $class);
    }

    /**
    * Register a class / callback that should be used to create Projects.
    *
    * @param  string  $class
    * @return void
    */
    public static function storeProjectsUsing(string $class)
    {
        return app()->singleton(StoresProjects::class, $class);
    }

    /**
    * Register a class / callback that should be used to display a given Project.
    *
    * @param  string  $class
    * @return void
    */
    public static function showProjectsUsing(string $class)
    {
        return app()->singleton(ShowsProjects::class, $class);
    }

    /**
    * Register a class / callback that should be used to display the necessary
    * objects for the updating of a given Project.
    *
    * @param  string  $class
    * @return void
    */
    public static function editProjectsUsing(string $class)
    {
        return app()->singleton(EditsProjects::class, $class);
    }

    /**
    * Register a class / callback that should be used to update a given Project.
    *
    * @param  string  $class
    * @return void
    */
    public static function updateProjectsUsing(string $class)
    {
        return app()->singleton(UpdatesProjects::class, $class);
    }

    /**
    * Register a class / callback that should be used to delete a given Project.
    *
    * @param  string  $class
    * @return void
    */
    public static function destroyProjectsUsing(string $class)
    {
        return app()->singleton(DestroysProjects::class, $class);
    }
}
