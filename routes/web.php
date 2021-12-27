<?php

use App\Http\Controllers\Embers\ChallengeController;
use App\Http\Controllers\Embers\DashboardController;
use App\Http\Controllers\Embers\HelpController;
use App\Http\Controllers\Embers\InstitutionController;
use App\Http\Controllers\Embers\LinkController;
use App\Http\Controllers\Embers\MarkAllNotificationsAsReadController;
use App\Http\Controllers\Embers\NotificationContoller;
use App\Http\Controllers\Embers\ObjectsController;
use App\Http\Controllers\Embers\ProjectController;
use App\Http\Controllers\Embers\ProjectSimulationController;
use App\Http\Controllers\Embers\ShareLinkController;
use App\Http\Controllers\Embers\ShareProjectController;
use App\Http\Controllers\Embers\ShareProjectSimulationController;
use App\Http\Controllers\Embers\ShareSinkController;
use App\Http\Controllers\Embers\ShareSourceController;
use App\Http\Controllers\Embers\ShowNewNotificationsController;
use App\Http\Controllers\Embers\SinkController;
use App\Http\Controllers\Embers\SourceController;
use App\Http\Controllers\Embers\TeamRolesController;
use App\Http\Controllers\Embers\MapDataController;
use App\Http\Controllers\Embers\NewsController;
use App\Http\Controllers\Embers\QuerySearchController;
use App\Http\Controllers\Embers\RemoveAllNotificationsController;
use App\Http\Controllers\Embers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', fn () => redirect('/login'));



Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Map data
    Route::resource('/map-data', MapDataController::class)->only(['index', 'store']);

    // Search
    Route::get('/search', SearchController::class)->name('search.index');
    Route::post('/search/query', QuerySearchController::class)->name('search.query');

    // Dashboard
    Route::resource('/dashboard', DashboardController::class)
        ->whereNumber(['dashboard']);

    // Notifications
    Route::get('/notifications/new', ShowNewNotificationsController::class)->name('notifications.new');
    Route::post('/notifications/remove-all', RemoveAllNotificationsController::class)->name('notifications.remove-all');
    Route::post('/notifications/mark-all-as-read', MarkAllNotificationsAsReadController::class)->name('notifications.mark-all-as-read');
    Route::resource('/notifications', NotificationContoller::class)->only(['index', 'update', 'destroy'])->whereUuid(['notification']);

    // Institution
    Route::resource('/institution', InstitutionController::class)->whereNumber(['institution']);

    // Objects.["locations", "sources", "sinks", "links"]
    Route::prefix('objects')->as('objects.')->group(function () {
        Route::get('/', [ObjectsController::class, 'map'])->name('index');
        Route::get('/list', [ObjectsController::class, 'index'])->name('list');
        Route::get('/map', [ObjectsController::class, 'markers'])->name('markers');

        // Sources
        Route::get('/sources/{source}/share', ShareSourceController::class)->name('sources.share')
            ->whereNumber(['source']);
        Route::resource('/sources', SourceController::class)->except(['index'])
            ->whereNumber(['source']);

        // Sinks
        Route::get('/sinks/{sink}/share', ShareSinkController::class)->name('sinks.share')
            ->whereNumber(['sink']);
        Route::resource('/sinks', SinkController::class)->except(['index'])
            ->whereNumber(['sink']);

        // Links
        Route::get('/links/{link}/share', ShareLinkController::class)->name('links.share')
            ->whereNumber(['link']);
        Route::resource('/links', LinkController::class)->except(['index'])
            ->whereNumber(['link']);
    });

    // Projects
    Route::get('/projects/{project}/share', ShareProjectController::class)->name('projects.share')
        ->whereNumber(['project']);

    Route::resource('/projects', ProjectController::class)
        ->whereNumber(['project']);

    // Simulations
    Route::get('/projects/{project}/simulations/{simulation}/share', ShareProjectSimulationController::class)->name('projects.simulations.share')
        ->whereNumber(['project', 'simulation']);

    Route::resource('/projects.simulations', ProjectSimulationController::class)
        ->whereNumber(['project', 'simulation']);

    // Challenge
    Route::resource('/challenges', ChallengeController::class)
        ->whereNumber(['chalenge']);

    // Help
    Route::resource('/help', HelpController::class)->only(['index'])
        ->whereNumber(['help']);

    // News
    Route::resource('/news', NewsController::class)->only(['index', 'show'])
        ->whereNumber(['news']);

    // TeamRoles
    Route::resource('/team-roles', TeamRolesController::class)
        ->whereNumber(['team_role']);
});

// Jetstream routes
require base_path('/routes/jetstream.php');
