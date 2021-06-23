<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\Contracts\Embers\Objects\Links\DestroysLinks;
use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Links\SharesLinks;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $links = app(IndexesLinks::class)->index($request->user());

        // return response()->json([
        //     'links' => $links
        // ]);

        return Redirect::route('objects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $props = app(CreatesLinks::class)->create($request->user());

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
        app(StoresLinks::class)->store($request->user(), $request->all());

        return Redirect::route('objects.links.index');
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
        $link = app(ShowsLinks::class)->show($request->user(), $id);

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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        [
            $links,
            $locations,
            $link
        ] = app(EditsLinks::class)->edit($request->user(), $id);

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
        $updatedLink = app(UpdatesLinks::class)->update($request->user(), $id, $request->all());

        return Redirect::route('objects.links.show', $updatedLink->id);
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
        app(DestroysLinks::class)->destroy($request->user(), $id);

        return Redirect::route('objects.index');
    }
}
