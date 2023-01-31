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
use App\Http\Controllers\Embers\RunProjectSimulationController;
use App\Http\Controllers\Embers\ShareProjectSimulationController;
use App\Http\Controllers\Embers\ShareSinkController;
use App\Http\Controllers\Embers\ShareSourceController;
use App\Http\Controllers\Embers\ShowNewNotificationsController;
use App\Http\Controllers\Embers\SinkController;
use App\Http\Controllers\Embers\SourceController;
use App\Http\Controllers\Embers\TeamRolesController;
use App\Http\Controllers\Embers\MapDataController;
use App\Http\Controllers\Embers\NewsController;
use App\Http\Controllers\Embers\ProjectSimulationSessionController;
use App\Http\Controllers\Embers\ProjectSimulationSessionReportController;
use App\Http\Controllers\Embers\QuerySearchController;
use App\Http\Controllers\Embers\RemoveAllNotificationsController;
use App\Http\Controllers\Embers\SearchController;
use App\Http\Controllers\Embers\MySimulationController;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Request;

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

Route::get('/', fn() => redirect('/login'));
Route::get('/config', function () {
    return response()->json([
        'pusherKey' => config('broadcasting.connections.pusher.key'),
        'pusherCluster' => config('broadcasting.connections.pusher.options.cluster'),
        'pusherHost' => config('broadcasting.connections.pusher.options.host'),
        'pusherTLS' => config('broadcasting.connections.pusher.options.useTLS'),
        'pusherPort' => config('websockets.dashboard.port'),
        'app_url' => config('app.url'),
        'user_id' => request()->user()->id ?? null
    ]);
})->name('config');


Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/storage/{disk}/{file}', function ($disk, $file) {
        return \Illuminate\Support\Facades\Storage::disk($disk)->download($file);
    });

    Route::get('/import-sample-download', function (Request $request) {
        $template = '';
        $query = Template::with('templateProperties', 'templateProperties.property')->orderBy("order");
        if ($request->input('id')) {
            $query = $query->where('id', $request->input('id'));
            $templates = $query->get();
            $template = optional($templates[0])->name;
        } else {
            $templates = $query->get();
        }
        $allProps = [
            'template' => $template,
            'latitude' => '',
            'longitude' => ''
        ];
        $templates->each(function ($item) use (&$allProps) {
            $item->templateProperties->sortBy('order')->each(function ($tempProp) use (&$allProps) {
                $allProps[$tempProp->property['symbolic_name']] = '';
            });
        });
        $header_style = (new StyleBuilder())
            ->setShouldWrapText()->setShouldShrinkToFit()
            ->build();

        return (new FastExcel([$allProps]))
            ->headerStyle($header_style)
            ->download('import_sample.xlsx');
    });
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

    Route::get('/sessions/{session}', [ProjectSimulationSessionController::class, 'show'])->name("session.show");
    Route::post('/json-report/{type}/{id}', [ProjectSimulationSessionController::class, 'jsonReport'])->name("session.json");
    Route::post('/csv-report/{session}', [ProjectSimulationSessionController::class, 'csvReport'])->name("session.csv");
    Route::delete('/sessions/{session}', [ProjectSimulationSessionController::class, 'destroy'])->name("session.delete");

    Route::get('/sessions/{session}/report/{report}', ProjectSimulationSessionReportController::class)->name('session.report.show');
    Route::get('/sessions/{session}/map', [ProjectSimulationSessionReportController::class, 'getMapData'])->name('session.map.show');
    Route::get('/sessions/{session}/final-report', [ProjectSimulationSessionReportController::class, 'getFinalReport'])->name('session.final-report.show');

    // Projects
    Route::get('/projects/{project}/share', ShareProjectController::class)->name('projects.share')
        ->whereNumber(['project']);

    Route::resource('/projects', ProjectController::class)
        ->whereNumber(['project']);

    Route::post('/import', [ProjectController::class, 'importFile']);
    Route::get('/sample-import', [ProjectController::class, 'downloadImportFile']);

    // My Simulations
    Route::resource('/my-simulations', MySimulationController::class)
        ->whereNumber(['my-simulations']);

    Route::post('/get-simulation', [MySimulationController::class, 'getSimulation']);
    Route::get('/progress/{simulation}', [MySimulationController::class, 'progress']);

    // Simulations
    Route::get('/projects/{project}/simulations/{simulation}/share', ShareProjectSimulationController::class)->name('projects.simulations.share')
        ->whereNumber(['project', 'simulation']);

    Route::resource('/projects.simulations', ProjectSimulationController::class)
        ->whereNumber(['project', 'simulation']);

    Route::post('/projects/{project}/simulations/{simulation}/run', RunProjectSimulationController::class)->name('projects.simulations.run')
        ->whereNumber(['project', 'simulation']);
    Route::post('/projects/{project}/simulations/{simulation}/update', \App\Http\Controllers\Embers\UpdateProjectSimulationController::class)->name('projects.simulations.run.update')
        ->whereNumber(['project', 'simulation']);

    Route::get('/teo-technology-from/{simulationUid}', [ProjectSimulationSessionController::class, 'getTechnologies'])->name('simulations.teo.technologies');
    // Challenge
    Route::resource('/challenges', ChallengeController::class)
        ->whereNumber(['chalenge']);

    Route::post('/enroll-challenge',[ChallengeController::class, 'enroll']);
    Route::post('/submit-challenge',[ChallengeController::class, 'submit']);

    // Help
    Route::resource('/help', HelpController::class)->only(['index'])
        ->whereNumber(['help']);

    Route::resource('/sinks', SinkController::class)
        ->whereNumber(['sink']);

    Route::post('/sinks/export', [SinkController::class, 'export']);

    Route::resource('/sources', SourceController::class)
        ->whereNumber(['source']);

    Route::post('/sources/export', [SourceController::class, 'export']);

    // News
    Route::resource('/news', NewsController::class)->only(['index', 'show'])
        ->whereNumber(['news']);

    // TeamRoles
    Route::resource('/team-roles', TeamRolesController::class)
        ->whereNumber(['team_role']);
});

// Jetstream routes
require base_path('/routes/jetstream.php');
