<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

class ObjectsController extends Controller
{
    public function index(Request $request)
    {
        $instances = $request->user()->currentTeam->instances()->with([
            'template',
            'template.category',
            'location',
        ])->get();


        $links = $request->user()->currentTeam->links;
        return [
            "slideOver" => 'Objects/ObjectsIndex',
            "props" => [
                "instances" => cleanCharacterization($instances),
                "links" => $links
            ]
        ];
    }

    public function map(Request $request): Response
    {
        $instances = $request->user()
            ->currentTeam
            ->instances()
            ->with([
                'template',
                'template.category',
                'location',
            ])
            ->get();


        return Inertia::render('Objects/Objects', ['instances' => cleanCharacterization($instances)]);
    }

    public function markers(Request $request)
    {
        $instances = $request->user()->currentTeam->instances()->with([
            'template',
            'template.category',
            'location',
        ])->get();
        $links = $request->user()->currentTeam->links()->with([
            'geoSegments'
        ])->get();

        return [
            "instances" => cleanCharacterization($instances),
            "links" => $links
        ];
    }
}
