<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Contracts\Embers\Objects\Sources\SharesSources;
use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\Contracts\Embers\Objects\Sources\UpdatesSources;
use App\Helpers\Nova\Action\DispatchCustomAction;
use App\Http\Controllers\Controller;
use App\Nova\Actions\InstanceProcessing;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Laravel\Nova\Fields\ActionFields;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = app(IndexesSources::class)->index();

        // return Inertia::render('Objects/Sources/SourceIndex', [
        //     'sources' => $sources
        // ]);

        return response()->json([
            'sources' => $sources
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        [
            $templates,
            $equipments,
            $equipmentsCategories,
            $processes,
            $processesCategories,
            $locations
        ] = app(CreatesSources::class)->create();

        return [
            "slideOver" => "Objects/Sources/SourceCreate",
            "props" => [
                "templates" => $templates,
                "equipments" => $equipments,
                "equipmentsCategories" => $equipmentsCategories,
                "processes" => $processes,
                "processesCategories" => $processesCategories,
                "locations" => $locations,
            ]
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        app(StoresSources::class)->store($request->all());

        return Redirect::route('objects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        [
            $templates,
            $equipments,
            $locations,
            $instance
        ] = app(ShowsSources::class)->show($id);

        $action = new InstanceProcessing();
        $user_id = Auth::user()->id;


        // DispatchCustomAction::dispatchAction(
        //     $action,
        //     new ActionFields(new Collection(), new Collection()),
        //     [$instance],
        //     $user_id
        // );

        $action->generateScriptFile($instance);


        return [
            "slideOver" => "Objects/Sources/SourceDetails",
            "props" => [
                "templates" => $templates,
                "equipments" => $equipments,
                "locations" => $locations,
                "instance" => $instance
            ]
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        [
            $templates,
            $equipments,
            $locations,
            $instance
        ] = app(EditsSources::class)->edit($id);




        return [
            "slideOver" => "Objects/Sources/SourceEdit",
            "props" => [
                "templates" => $templates,
                "equipments" => $equipments,
                "locations" => $locations,
                "instance" => $instance
            ]
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updatedSource = app(UpdatesSources::class)->update($id, $request->all());

        return Redirect::route('objects.sources.show', $updatedSource->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        app(DestroysSources::class)->destroy($id);

        return Redirect::route('objects.index');
    }

    /**
     * Share the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function share($id)
    {
        $source = app(SharesSources::class)->share($id);

        // return [
        //     "slideOver" => 'Objects/Sinks/SinkShare',
        //     "props" => [
        //         "instance" => $sink
        //     ]
        // ];

        return response()->json([
            "source" => $source
        ]);
    }
}
