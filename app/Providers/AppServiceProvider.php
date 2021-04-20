<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\ResponseFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResponseFactory::macro('slideOver', function ($slideOver, $props) {
            inertia()->share(['slideOver' => $slideOver, 'slideOverProps' => $props]);
        });
    }
}
