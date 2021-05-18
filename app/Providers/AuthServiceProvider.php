<?php

namespace App\Providers;

use App\Models\Instance;
use App\Models\Link;
use App\Models\Team;
use App\Policies\Embers\InstancePolicy;
use App\Policies\Embers\LinkPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        Instance::class => InstancePolicy::class, // policies for sources & sinks
        Link::class => LinkPolicy::class // policies for links
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
