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

function createResourceNames($prefix)
{
    return [
        'index' => "$prefix.index",
        'create' => "$prefix.create",
        'store' => "$prefix.store",
        'show' => "$prefix.show",
        'edit' => "$prefix.edit",
        'update' => "$prefix.update",
        'destroy' => "$prefix.destroy"
    ];
}

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
        Route::resource('/location', [LocationController::class])->names(createResourceNames('location'));
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
