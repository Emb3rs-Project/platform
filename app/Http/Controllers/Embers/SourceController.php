<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Objects\CreatesSources;
use App\Contracts\Embers\Objects\UpdatesSources;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GeoObject;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('viewAny', Instance::class);

        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

        return $sourceCategories;

        $templates = Template::whereIn('category_id', $sourceCategories)
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

        // return response()->json([
        //     'sources' => $output
        // ]);

        return Inertia::render('Objects/Sources/SourceIndex', [
            'sources' => $output
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

        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get();

        $processCategories = Category::whereType('process')
            ->get();

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $equipmentTemplates = Template::whereIn('category_id', $equipmentCategories->map(fn ($e) => $e->id))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        $locations = Location::with(['geoObject'])->get();

        $processTemplates = Template::whereIn('category_id', $processCategories->map(fn ($p) => $p->id))
            ->with([
                'templateProperties',
                'templateProperties.unit',
                'templateProperties.property'
            ])
            ->get();

        return [
            "slideOver" => "Objects/Sources/SourceCreate",
            "props" => [
                "templates" => $sourceTemplates,
                "equipments" => $equipmentTemplates,
                "equipmentsCategories" => $equipmentCategories,
                "processes" => $processTemplates,
                "processesCategories" => $processCategories,
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
        app(CreatesSources::class)->create($request->user(), $request->all());

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
        $source = Instance::findOrFail($id);

        Gate::authorize('view', $source);

        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get()
            ->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
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
            ->with([
                'location',
                'template',
                'template.category',
                'location.geoObject'
            ])
            ->first();

        return [
            "slideOver" => "Objects/Sources/SourceDetails",
            "props" => [
                "templates" => $sourceTemplates,
                "equipments" => $equipmentTemplates,
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
        $source = Instance::findOrFail($id);

        Gate::authorize('view', $source);

        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

        $equipmentCategories = Category::whereType('equipment')
            ->get()
            ->pluck('id');

        $sourceTemplates = Template::whereIn('category_id', $sourceCategories)
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

        $instance = Instance::whereId($id)->with(['location', 'template', 'template.category', 'location.geoObject'])->first();

        return Inertia::render(
            'Objects/Sources/SourceEdit',
            [
                "templates" => $sourceTemplates,
                "equipments" => $equipmentTemplates,
                "locations" => $locations,
                "instance" => $instance
            ]
        );
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
        $source = Instance::findOrFail($id);

        $updatedSource = app(UpdatesSources::class)->update($request->user(), $source, $request->all());

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
        $source = Instance::findOrFail($id);

        Gate::authorize('delete', $source);

        Instance::destroy($source->id);

        return Redirect::route('objects.index');
    }
}
