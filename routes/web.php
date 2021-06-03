<?php

use App\Http\Controllers\Embers\ChallengeController;
use App\Http\Controllers\Embers\DashboardController;
use App\Http\Controllers\Embers\HelpController;
use App\Http\Controllers\Embers\InstitutionController;
use App\Http\Controllers\Embers\LinkController;
use App\Http\Controllers\Embers\LocationController;
use App\Http\Controllers\Embers\ObjectsController;
use App\Http\Controllers\Embers\ProjectController;
use App\Http\Controllers\Embers\ProjectSimulationController;
use App\Http\Controllers\Embers\SinkController;
use App\Http\Controllers\Embers\SourceController;
use App\Http\Controllers\Embers\TeamRolesController;
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

function createResourceNames($prefix)
{
    return [
        'index' => "$prefix.index",
        'create' => "$prefix.create",
        'store' => "$prefix.store",
        'show' => "$prefix.show",
        'edit' => "$prefix.edit",
        'update' => "$prefix.update",
        'destroy' => "$prefix.destroy",
    ];
}

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Dashboard
    Route::resource('/dashboard', DashboardController::class)->names(createResourceNames('dashboard'));

    // Institution
    Route::resource('/institution', InstitutionController::class)->names(createResourceNames('institution'));

    // Objects.["locations", "sources", "sinks", "links"]
    Route::group(['prefix' => 'objects', 'as' => 'objects.'], function () {
        Route::get('/', [ObjectsController::class, 'map'])->name('index');
        Route::get('/list', [ObjectsController::class, 'index'])->name('list');
        Route::get('/map', [ObjectsController::class, 'markers'])->name('markers');

        // Locations
        Route::resource('/locations', LocationController::class)->names(createResourceNames('locations'));

        // Sources
        Route::get('/sources/{id}/share', [SourceController::class, "share"])->name('sources.share');
        Route::resource('/sources', SourceController::class)->names(createResourceNames('sources'));

        // Sinks
        Route::get('/sinks/{id}/share', [SinkController::class, "share"])->name('sinks.share');
        Route::resource('/sinks', SinkController::class)->names(createResourceNames('sinks'));

        // Links
        Route::get('/links/{id}/share', [LinkController::class, "share"])->name('links.share');
        Route::resource('/links', LinkController::class)->names(createResourceNames('links'));
    });

    // Projects
    Route::get('/projects/{id}/share', [ProjectController::class, "share"])->name('projects.share');
    Route::resource('/projects', ProjectController::class)->names(createResourceNames('projects'));

    // Simulations
    Route::get('/projects/{projectId}/simulations/{simulationId}/share', [ProjectSimulationController::class, "share"])->name('projects.simulations.share');
    Route::resource('/projects.simulations', ProjectSimulationController::class)->names(createResourceNames('projects.simulations'));

    // Challenge
    Route::resource('/chalenge', ChallengeController::class)->names(createResourceNames('challenge'));

    // Help
    Route::resource('/help', HelpController::class)->names(createResourceNames('help'));

    // TeamRoles
    Route::resource('/team-roles', TeamRolesController::class)->names(createResourceNames('team-roles'));
});
