<?php

namespace App\Providers;

use App\Actions\Embers\Objects\Sinks\CreateSink;
use App\Actions\Embers\Objects\Sinks\DestroySink;
use App\Actions\Embers\Objects\Sinks\EditSink;
use App\Actions\Embers\Objects\Sinks\IndexSink;
use App\Actions\Embers\Objects\Sinks\ShowSink;
use App\Actions\Embers\Objects\Sinks\StoreSink;
use App\Actions\Embers\Objects\Sinks\UpdateSink;
use App\Actions\Embers\Objects\Sources\CreateSource;
use App\Actions\Embers\Objects\Sources\DestroySource;
use App\Actions\Embers\Objects\Sources\EditSource;
use App\Actions\Embers\Objects\Sources\IndexSource;
use App\Actions\Embers\Objects\Sources\ShowSource;
use App\Actions\Embers\Objects\Sources\StoreSource;
use App\Actions\Embers\Objects\Sources\UpdateSource;
use App\Embers;
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
            inertia()->share([
                'slideOver' => $slideOver,
                'slideOverProps' => $props
            ]);
        });

        Embers::indexSinksUsing(IndexSink::class);
        Embers::createSinksUsing(CreateSink::class);
        Embers::storeSinksUsing(StoreSink::class);
        Embers::showSinksUsing(ShowSink::class);
        Embers::editSinksUsing(EditSink::class);
        Embers::updateSinksUsing(UpdateSink::class);
        Embers::destroySinksUsing(DestroySink::class);

        Embers::indexSourcesUsing(IndexSource::class);
        Embers::createSourcesUsing(CreateSource::class);
        Embers::showSourcesUsing(ShowSource::class);
        Embers::storeSourcesUsing(StoreSource::class);
        Embers::editSourcesUsing(EditSource::class);
        Embers::updateSourcesUsing(UpdateSource::class);
        Embers::destroySourcesUsing(DestroySource::class);

        //Links
        // TODO
    }
}
