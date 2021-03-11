<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Instance;
use App\Models\Template;
use Illuminate\Http\Request;
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
        $sourceCategories = Category::whereType('source')
            ->get()
            ->pluck('id');

        $templates = Template::whereIn('category_id', $sourceCategories)
            ->get();

        $instances = Instance::whereIn('template_id', $templates)
            ->with(['template', 'template.category', 'location.geoObject'])
            ->get();

        $output = $instances->map(function ($item) {
            $item['data'] = $item->location->geoObject;

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

        $templates = Template::whereIn('category_id', $sourceCategories)
            ->with(['templateProperties','templateProperties.unit'])
            ->get();

        return Inertia::render('Objects/Sources/SourceCreate', ["templates" => $templates]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
