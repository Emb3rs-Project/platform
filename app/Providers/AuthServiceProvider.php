<?php

namespace App\Providers;

use App\Models\Instance;
use App\Models\Link;
use App\Models\Project;
use App\Models\Simulation;
use App\Models\Team;
use App\Policies\Embers\InstancePolicy;
use App\Policies\Embers\LinkPolicy;
use App\Policies\Embers\ProjectPolicy;
use App\Policies\Embers\SimulationPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
    ];
}
