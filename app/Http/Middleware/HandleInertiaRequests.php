<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;
use Laravel\Jetstream\Jetstream;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            // This is being used to override the 'user' prop from ShareInertiaData
            // 'user' => fn () => $request->user()
            //     ? count($request->user()->unreadNotifications)
            //     : null,
            'user' => function () use ($request) {
                if (! $request->user()) {
                    return;
                }

                return array_merge(
                    Arr::except($request->user()->toArray(), [
                        'current_team.instances'
                    ]),
                    array_filter([
                    'all_teams' => Jetstream::hasTeamFeatures() ? $request->user()->allTeams() : null
                ]),
                    [
                        'two_factor_enabled' => ! is_null($request->user()->two_factor_secret)
                    ]
                );
            },
        ]);
    }
}
