<?php

use App\Http\Controllers\Embers\ChallengeController;
use App\Http\Controllers\Embers\DashboardController;
use App\Http\Controllers\Embers\HelpController;
use App\Http\Controllers\Embers\InstitutionController;
use App\Http\Controllers\Embers\LinkController;
use App\Http\Controllers\Embers\LocationController;
use App\Http\Controllers\Embers\SimulationController;
use App\Http\Controllers\Embers\SinkController;
use App\Http\Controllers\Embers\SourceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Institution
    Route::get('/institution', [InstitutionController::class, 'index'])->name('institution');

    // Objects.["locations", "sources", "sinks", "links"]
    Route::group(['prefix'=>'objects','as'=>'objects.'], function () {
        // Locations
        Route::get('/locations', [LocationController::class, 'index'])->name('locations');
        Route::get('/locations/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');

        // Sources
        Route::get('/sources', [SourceController::class, 'index'])->name('sources');

        // Sinks
        Route::get('/sinks', [SinkController::class, 'index'])->name('sinks');

        // Links
        Route::get('/links', [LinkController::class, 'index'])->name('links');
    });

    // Simulations
    Route::get('/simulations', [SimulationController::class, 'index'])->name('simulations');

    // Challenge
    Route::get('/chalenge', [ChallengeController::class, 'index'])->name('challenge');

    // Help
    Route::get('/help', [HelpController::class, 'index'])->name('help');
});
