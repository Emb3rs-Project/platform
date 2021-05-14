<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\CreatesSinks;
use App\Contracts\Embers\Objects\UpdatesSinks;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
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
        Gate::authorize('viewAny', Instance::class);

        $sinkCategories = Category::whereType('sink')
            ->get()
            ->pluck('id');

        $templates = Template::whereIn('category_id', $sinkCategories)
            ->get()
            ->pluck('id');

        $teamInstances = Auth::user()->currentTeam->instances->pluck('id');

        $instances = Instance::whereIn('template_id', $templates)
            ->whereIn('id', $teamInstances)
            ->with(['template', 'template.category', 'location.geoObject'])
            ->get();

        $output = $instances->map(function ($item) {
            if (isset($item->location)) {
                $item['data'] = $item->location->geoObject;
            }

            return $item;
        });

        return Inertia::render('Objects/Sinks/SinkIndex', [
            'sinks' => $output
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', Instance::class);

        $sinkCategories = Category::whereType('sink')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get()
            ->pluck('id');


        $sinkTemplates = Template::whereIn('category_id', $sinkCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $equipmentTemplates = Template::whereIn('category_id', $equipmentCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::with(['geoObject'])->get();

        return [
            "slideOver" => 'Objects/Sinks/SinkCreate',
            "props" => [
                "templates" => $sinkTemplates,
                "equipments" => $equipmentTemplates,
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
        app(CreatesSinks::class)->create($request->user(), $request->all());

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
        $sink = Instance::findOrFail($id);

        Gate::authorize('view', $sink);

        $instance = Instance::whereId($id)
            ->with([
                'location',
                'template',
                'template.category',
                'location.geoObject'
            ])
            ->first();

        return [
            "slideOver" => 'Objects/Sinks/SinkDetails',
            "props" => [
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
        $sink = Instance::findOrFail($id);

        Gate::authorize('view', $sink);

        $sinkCategories = Category::whereType('sink')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get()
            ->pluck('id');

        $sinkTemplates = Template::whereIn('category_id', $sinkCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $equipmentTemplates = Template::whereIn('category_id', $equipmentCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::with(['geoObject'])->get();

        $instance = Instance::whereId($id)
            ->with(['location', 'template', 'template.category', 'location.geoObject'])
            ->first();

        return [
            "slideOver" => 'Objects/Sinks/SinkEdit',
            "props" => [
                "templates" => $sinkTemplates,
                "equipments" => $equipmentTemplates,
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
        $sink = Instance::findOrFail($id);

        $updatedSink = app(UpdatesSinks::class)->update($request->user(), $sink, $request->all());

        return Redirect::route('objects.sinks.show', $updatedSink->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sink = Instance::findOrFail($id);

        Gate::authorize('delete', $sink);

        Instance::destroy($sink->id);

        return Redirect::route('objects.index');
    }
}
