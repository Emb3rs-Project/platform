<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
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

        $instances = Instance::whereIn('template_id', $templates)
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

        return Inertia::render(
            'Objects/Sources/SourceCreate',
            [
            "templates" => $sourceTemplates,
            "equipments" => $equipmentTemplates,
            "locations" => $locations
            ]
        );
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

        // Check if Property Name Exists
        if (isset($source['data']['name'])) {
            $newInstance['name'] = $source['data']['name'];
        }

        // Check if Location is Set
        if (isset($source['location_id'])) {
            $newInstance['location_id'] = $source['location_id'];
        }



        Instance::create($newInstance);

        return Redirect::route('objects.sources.index');
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

        $instance = Instance::whereId($id)->with(['location','template','template.category', 'location.geoObject'])->first();

        return Inertia::render(
            'Objects/Sources/SourceDetails',
            [
            "templates" => $sourceTemplates,
            "equipments" => $equipmentTemplates,
            "locations" => $locations,
            "instance" => $instance
            ]
        );
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

        $instance = Instance::whereId($id)->with(['location','template','template.category', 'location.geoObject'])->first();

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
        //
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
        return redirect::route('objects.sources.index');
    }
}
