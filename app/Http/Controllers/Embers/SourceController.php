<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
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

        return Inertia::render(
            'Objects/Sources/SourceCreate',
            [
            "templates" => $sourceTemplates,
            "equipments" => $equipmentTemplates]
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

        $name = 'Not Defined';
        if (isset($source['data']['name'])) {
            $name = $source['data']['name'];
        }


        Instance::create([
            "name" => $name,
            "values" => [
                "equipments" => $equipments
            ],
            "template_id" => $request->get('template_id')
        ]);


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
        return redirect::route('objects.sources.index');
    }
}
