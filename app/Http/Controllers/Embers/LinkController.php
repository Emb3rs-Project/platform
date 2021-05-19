<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Links\CreatesLinks;
use App\Contracts\Embers\Objects\Links\EditsLinks;
use App\Contracts\Embers\Objects\Links\IndexesLinks;
use App\Contracts\Embers\Objects\Links\ShowsLinks;
use App\Contracts\Embers\Objects\Links\StoresLinks;
use App\Contracts\Embers\Objects\Links\UpdatesLinks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = app(IndexesLinks::class)->index(Auth::user());

        return Inertia::render('Objects/Links/LinkIndex', [
            'links' => $links
        ]);
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
        app(StoresLinks::class)->store(Auth::user(), $request->all());

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
        $link = app(ShowsLinks::class)->show(Auth::user(), $id);

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
        ] = app(EditsLinks::class)->edit(Auth::user(), $id);

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
        $updatedLink = app(UpdatesLinks::class)->update(Auth::user(), $id, $request->all());

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
        // app(DestroysSinks::class)->destroy(Auth::user(), $id);

        return Redirect::route('objects.index');
    }
}
