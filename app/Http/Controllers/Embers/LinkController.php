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

class LinkController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function create(Request $request)
    {
        [
            $templates,
            $locations
        ] = app(CreatesLinks::class)->create($request->user());

        return [
            "slideOver" => 'Objects/Links/LinkCreate',
            'props' => [
                'templates' => $templates,
                'locations' => $locations
            ]
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $link = app(StoresLinks::class)->store($request->user(), $request->all());

        $linkName = $link->name;
        $team = $request->user()->currentTeam;
        $message = 'created a new Link at';
        $tag = [['name' => "Link #$linkName", 'path' => 'objects.links.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $link->id);

        return redirect()->route('objects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array<string, mixed>
     */
    public function show(Request $request, $id)
    {
        [$link, $templateProperties] = app(ShowsLinks::class)->show($request->user(), $id);

        return [
            "slideOver" => 'Objects/Links/LinkDetails',
            "props" => [
                "instance" => $link,
                'templateProperties' => $templateProperties,
            ]
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array<string, mixed>
     */
    public function edit(Request $request, $id)
    {
        [
            $templates,
            $link
        ] = app(EditsLinks::class)->edit($request->user(), $id);

        return [
            "slideOver" => 'Objects/Links/LinkEdit',
            "props" => [
                "templates" => $templates,
                "instance" => $link
            ]
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $link = app(UpdatesLinks::class)->update(
            $request->user(),
            $id,
            $request->all()
        );

        $linkName = $link->name;
        $team = $request->user()->currentTeam;
        $message = 'updated a Link at';
        $tag = [['name' => "Link #$linkName", 'path' => 'objects.links.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $link->id);

        return redirect()->route('objects.index');
        //return redirect()->route('objects.links.show', $updatedLink->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $link = app(DestroysLinks::class)->destroy($request->user(), $id);

        $linkName = $link->name;
        $team = $request->user()->currentTeam;
        $message = 'destroyed a Link at';
        $tag = [['name' => "Link #$linkName", 'path' => 'objects.links.show']];
        app(NotificationContoller::class)->objectNotify($request->user(), $team, $tag, $message, $link->id);

        return redirect()->route('objects.index');
    }
}
