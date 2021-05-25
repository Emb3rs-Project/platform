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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sinks = app(IndexesSinks::class)->index();

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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        [$templates, $equipments, $locations] = app(CreatesSinks::class)->create();

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
        $t = app(StoresSinks::class)->store($request->all());

        // return response()->json([
        //     'sink' => $t
        // ]);

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
        $sink = app(ShowsSinks::class)->show($id);

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
        ] = app(EditsSinks::class)->edit($id);

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
        $updatedSink = app(UpdatesSinks::class)->update($id, $request->all());

        // return Redirect::route('objects.sinks.show', $updatedSink->id);
        return Redirect::route('objects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        app(DestroysSinks::class)->destroy($id);

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
        $sink = app(SharesSinks::class)->show($id);

        // return [
        //     "slideOver" => 'Objects/Sinks/SinkShare',
        //     "props" => [
        //         "instance" => $sink
        //     ]
        // ];

        return response()->json([
            "instance" => $sink
        ]);
    }
}
