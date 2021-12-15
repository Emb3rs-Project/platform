<?php

use App\Http\Controllers\Embers\IntegrationCharacterizationController;
use App\Http\Controllers\Embers\IntegrationSimulationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['reporter'])->group(function () {
    Route::prefix('/integration/reporter')->as('integration.reporter')->group(function () {
        Route::post('/characterization', IntegrationCharacterizationController::class)->name('characterization');
        Route::post('/simulation', IntegrationSimulationController::class)->name('simulation');
        Route::post('/step')->name('step');
    });
});
