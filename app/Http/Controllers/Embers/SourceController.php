<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GeoObject;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

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


        return Inertia::render(
            'Objects/Sources/SourceIndex',
            [
                'sources' => $output
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $source = $request->get('source');
        $equipments = $request->get('equipments');
        foreach ($equipments as $key => $value) {
            unset($equipments[$key]['template']);
        }

        $newInstance = [
            "name" => 'Not Defined',
            "values" => [
                "equipments" => $equipments
            ],
            "template_id" => $request->get('template_id'),
            "location_id" => null
        ];

        if (is_array($request["location_id"])) {
            $marker = $request["location_id"];
            $geo = GeoObject::create([
                'type' => 'point',
                'data' => [
                    "center" => [$marker["lat"], $marker["lng"]]
                ]
            ]);

            $location = Location::create([
                'name' => $source["name"],
                'geo_object_id' => $geo->id
            ]);
            $newInstance['location_id'] = $location->id;
        } else {
            // Check if Location is Set
            $locationId = $request->input('location_id');
            if ($locationId) {
                $newInstance['location_id'] = $locationId;
            }
        }


        // Check if Property Name Exists
        if (isset($source['name'])) {
            $newInstance['name'] = $source['name'];
        }


        $newInstance["values"] = [
            "equipments" => $equipments,
            "info" => $source
        ];


        $instace = Instance::create($newInstance);
        $instace->teams()->attach(Auth::user()->currentTeam);

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
        $source = $request->get('source');
        $equipments = $request->get('equipments');
        foreach ($equipments as $key => $value) {
            unset($equipments[$key]['template']);
        }

        $instance = Instance::find($id);

        // Check if Property Name Exists
        if (isset($source['data']['name'])) {
            $instance->name = $source['data']['name'];
        }

        // Check if Location is Set
        if (isset($source['location_id'])) {
            $instance->location_id = $source['location_id'];
        } else {
            $instance->location()->disassociate();
        }

        $instance->save();

        return Redirect::route('objects.sources.show', $instance->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Instance::destroy($id);

        return Redirect::route('objects.index');
    }
}
