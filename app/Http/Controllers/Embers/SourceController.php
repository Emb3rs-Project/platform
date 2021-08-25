<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\Sources\CreatesSources;
use App\Contracts\Embers\Objects\Sources\DestroysSources;
use App\Contracts\Embers\Objects\Sources\EditsSources;
use App\Contracts\Embers\Objects\Sources\IndexesSources;
use App\Contracts\Embers\Objects\Sources\ShowsSources;
use App\Contracts\Embers\Objects\Sources\StoresSources;
use App\Contracts\Embers\Objects\Sources\UpdatesSources;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $sources = app(IndexesSources::class)->index($request->user());

        return response()->json([
            'sources' => $sources
        ]);
    }

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
            $equipment,
            $equipmentCategories,
            $processes,
            $processesCategories,
            $locations
        ] = app(CreatesSources::class)->create($request->user());

        return [
            "slideOver" => "Objects/Sources/SourceCreate",
            "props" => [
                "templates" => $templates,
                "equipment" => $equipment,
                "equipmentCategories" => $equipmentCategories,
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        app(StoresSources::class)->store($request->user(), $request->all());

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
        [
            $instance,
            $equipmentTemplates,
            $processesTemplates
        ] = app(ShowsSources::class)->show($request->user(), $id);

        return [
            "slideOver" => "Objects/Sources/SourceDetails",
            "props" => [
                "instance" => $instance,
                "equipment" => $equipmentTemplates,
                "processes" => $processesTemplates
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
            $instance,
            $templates,
            $locations,
            $equipment,
            $equipmentCategories,
            $processes,
            $processesCategories,
        ] = app(EditsSources::class)->edit($request->user(), $id);

        return [
            "slideOver" => "Objects/Sources/SourceEdit",
            "props" => [
                "instance" => $instance,
                "templates" => $templates,
                "locations" => $locations,
                "equipment" => $equipment,
                "equipmentCategories" => $equipmentCategories,
                "processes" => $processes,
                "processesCategories" => $processesCategories,
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
        $updatedSource = app(UpdatesSources::class)->update($request->user(), $id, $request->all());

        // return redirect()->route('objects.sources.show', $updatedSource->id);
        return redirect()->route('objects.index');
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
        app(DestroysSources::class)->destroy($request->user(), $id);

        return redirect()->route('objects.index');
    }
}
