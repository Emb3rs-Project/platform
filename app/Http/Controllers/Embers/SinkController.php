<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Location;
use App\Models\Template;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Redirect;

class SinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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


        return Inertia::render(
            'Objects/Sinks/SinkIndex',
            [
                'sinks' => $output
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
        $sink = $request->input('sink');
        $equipments = $request->input('equipments');
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
        if (isset($sink['data']['name'])) {
            $newInstance['name'] = $sink['data']['name'];
        }

        // Check if Location is Set
        $locationId = $request->input('location_id');
        if ($locationId) {
            $newInstance['location_id'] = $locationId;
        }

        $instace = Instance::create($newInstance);
        $instace->teams()->attach(Auth::user()->currentTeam);

        // @geocfu: i must return to the objectsindex page
        // return Inertia::render(
        //     'Objects/Objects'
        // );
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
            ->with(['location','template','template.category', 'location.geoObject'])
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
    public function update(Request $request, $id)
    {
        $sink = $request->get('sink');
        $equipments = $request->get('equipments');
        foreach ($equipments as $key => $value) {
            unset($equipments[$key]['template']);
        }

        $instance = Instance::find($id);

        // Check if Property Name Exists
        if (isset($sink['data']['name'])) {
            $instance->name = $sink['data']['name'];
        }

        $locationId = $request->input('location_id');
        if ($locationId) {
            $newInstance['location_id'] = $locationId;
        } else {
            $instance->location()->disassociate();
        }

        $instance->save();

        return Redirect::route('objects.sinks.show', $instance->id);
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
