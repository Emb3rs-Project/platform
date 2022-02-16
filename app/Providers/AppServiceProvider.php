<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
        if ($this->app->environment('local')) {
            Model::preventLazyLoading();
        }

        // TODO: THIS IS SO WE ARE BACKWARDS COMPATIBLE WITH LARAVEL 8.X. YOU MAY
        // REMOVE THE BELOW LINE ONCE YOU ARE SURE THAT IT DOES NOT BREAK ANYTHING
        Validator::includeUnvalidatedArrayKeys();
    }
}
