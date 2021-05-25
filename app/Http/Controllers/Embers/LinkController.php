<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\Contracts\Embers\Objects\Links\UpdatesLinks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = app(IndexesLinks::class)->index();

        // return [
        //     'slideOver' => 'Objects/Links/LinkIndex',
        //     'props' => [
        //         'links' => $links
        //     ]
        // ];

        // return response()->json([
        //     'links' => $links
        // ]);

        return Redirect::route('objects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $props = app(CreatesLinks::class)->create();

        return [
            "slideOver" => 'Objects/Links/LinkCreate',
            "props" => $props
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
        app(StoresLinks::class)->store($request->all());

        return Redirect::route('objects.links.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $link = app(ShowsLinks::class)->show($id);

        return [
            "slideOver" => 'Objects/Links/LinkDetails',
            "props" => [
                "instance" => $link
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
            $links,
            $locations,
            $link
        ] = app(EditsLinks::class)->edit($id);

        return [
            "slideOver" => 'Objects/Links/LinkEdit',
            "props" => [
                "links" => $links,
                "locations" => $locations,
                "instance" => $link
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
        $updatedLink = app(UpdatesLinks::class)->update($id, $request->all());

        return Redirect::route('objects.links.show', $updatedLink->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        app(DestroysLinks::class)->destroy($id);

        return Redirect::route('objects.index');
    }
}
