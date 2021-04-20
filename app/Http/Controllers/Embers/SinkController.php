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
        $sourceCategories = Category::whereType('sink')
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

        return [
            "slideOver" => 'Objects/Sinks/SinkCreate',
            "props" => [
                "templates" => $sourceTemplates,
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
        $sink = $request->get('sink');
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
        if (isset($sink['data']['name'])) {
            $newInstance['name'] = $sink['data']['name'];
        }

        // Check if Location is Set
        if (isset($sink['location_id'])) {
            $newInstance['location_id'] = $sink['location_id'];
        }

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

        return redirect::route('objects.sinks.index');
    }
}
