<?php

use App\Http\Controllers\Embers\ChallengeController;
use App\Http\Controllers\Embers\DashboardController;
use App\Http\Controllers\Embers\HelpController;
use App\Http\Controllers\Embers\InstitutionController;
use App\Http\Controllers\Embers\LinkController;
use App\Http\Controllers\Embers\LocationController;
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

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Map data
    Route::resource('/map-data', MapDataController::class)->only(['index', 'store']);

    // Dashboard
    Route::resource('/dashboard', DashboardController::class);

    // Notifications
    Route::resource('/notifications', NotificationContoller::class)->only(['index', 'destroy']);
    Route::get('/notifications/new', ShowNewNotificationsController::class)->name('notifications.new');
    Route::post('/notifications/mark-all-as-read', MarkAllNotificationsAsReadController::class)->name('notifications.mark-all-as-read');

    // Institution
    Route::resource('/institution', InstitutionController::class);

    // Objects.["locations", "sources", "sinks", "links"]
    Route::prefix('objects')->as('objects.')->group(function () {
        Route::get('/', [ObjectsController::class, 'map'])->name('index');
        Route::get('/list', [ObjectsController::class, 'index'])->name('list');
        Route::get('/map', [ObjectsController::class, 'markers'])->name('markers');

        // Locations
        Route::resource('/locations', LocationController::class);

        // Sources
        Route::get('/sources/{source}/share', ShareSourceController::class)->name('sources.share');
        Route::resource('/sources', SourceController::class);

        // Sinks
        Route::get('/sinks/{sink}/share', ShareSinkController::class)->name('sinks.share');
        Route::resource('/sinks', SinkController::class);

        // Links
        Route::get('/links/{link}/share', ShareLinkController::class)->name('links.share');
        Route::resource('/links', LinkController::class);
    });

    // Projects
    Route::get('/projects/{project}/share', ShareProjectController::class)->name('projects.share');
    Route::resource('/projects', ProjectController::class);

    // Simulations
    Route::get('/projects/{project}/simulations/{simulation}/share', ShareProjectSimulationController::class)->name('projects.simulations.share');
    Route::resource('/projects.simulations', ProjectSimulationController::class);

    // Challenge
    Route::resource('/challenge', ChallengeController::class);

    // Help
    Route::resource('/help', HelpController::class);

    // TeamRoles
    Route::resource('/team-roles', TeamRolesController::class);
});

// Jetstream routes
require base_path('/routes/jetstream.php');
