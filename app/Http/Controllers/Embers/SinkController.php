<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Sinks\CreatesSinks;
use App\Contracts\Embers\Objects\Sinks\DestroysSinks;
use App\Contracts\Embers\Objects\Sinks\EditsSinks;
use App\Contracts\Embers\Objects\Sinks\IndexesSinks;
use App\Contracts\Embers\Objects\Sinks\SharesSinks;
use App\Contracts\Embers\Objects\Sinks\ShowsSinks;
use App\Contracts\Embers\Objects\Sinks\StoresSinks;
use App\Contracts\Embers\Objects\Sinks\UpdatesSinks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Redirect;

class SinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sinks = app(IndexesSinks::class)->index($request->user());

        // return Inertia::render('Objects/Sinks/SinkIndex', [
        //     'sinks' => $sinks
        // ]);

        return response()->json([
            'sinks' => $sinks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        [$templates, $equipments, $locations] = app(CreatesSinks::class)->create($request->user());

        return [
            "slideOver" => 'Objects/Sinks/SinkCreate',
            "props" => [
                "templates" => $templates,
                "equipments" => $equipments,
                "locations" => $locations
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
        $t = app(StoresSinks::class)->store($request->user(), $request->all());

        // return response()->json([
        //     'sink' => $t
        // ]);

        return Redirect::route('objects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $sink = app(ShowsSinks::class)->show($request->user(), $id);

        return [
            "slideOver" => 'Objects/Sinks/SinkDetails',
            "props" => [
                "instance" => $sink
            ]
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        [
            $templates,
            $equipments,
            $locations,
            $instance
        ] = app(EditsSinks::class)->edit($request->user(), $id);

        return [
            "slideOver" => 'Objects/Sinks/SinkEdit',
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
    public function update(Request $request, int $id)
    {
        $updatedSink = app(UpdatesSinks::class)->update($request->user(), $id, $request->all());

        // return Redirect::route('objects.sinks.show', $updatedSink->id);
        return Redirect::route('objects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        app(DestroysSinks::class)->destroy($request->user(), $id);

        return Redirect::route('objects.index');
    }

    /**
     * Share the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function share(Request $request, $id)
    {
        $sink = app(SharesSinks::class)->share($request->user(), $id);

        // return [
        //     "slideOver" => 'Objects/Sinks/SinkShare',
        //     "props" => [
        //         "instance" => $sink
        //     ]
        // ];

        return response()->json([
            "sink" => $sink
        ]);
    }
}
