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
        $teamInstances = $request->user()->currentTeam->instances->pluck('id');

        $instances = Instance::query()
            ->with([
                'template',
                'template.category',
                'location',
            ])->whereIn('id', $teamInstances)
            ->get();

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
        $teamInstances = $request->user()->currentTeam->instances->pluck('id');

        $instances = Instance::with([
            'template',
            'template.category',
            'location',
        ])->whereIn('id', $teamInstances)->get();


        return Inertia::render('Objects/Objects', ['instances' => cleanCharacterization($instances)]);
    }

    public function markers(Request $request)
    {
        $teamInstances = $request->user()->currentTeam->instances->pluck('id');
        $teamLinks = $request->user()->currentTeam->links->pluck('id');

        $instances = Instance::with([
            'template',
            'template.category',
            'location',
        ])->whereIn('id', $teamInstances)
        ->get();

        $links = Link::with([
            'geoSegments'
        ])->whereIn('id', $teamLinks)->get();

        return [
            "instances" => cleanCharacterization($instances),
            "links" => $links
        ];
    }
}
