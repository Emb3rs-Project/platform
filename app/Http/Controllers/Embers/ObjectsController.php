<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use App\Models\Link;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ObjectsController extends Controller
{
    public function index(Request $request)
    {
        $teamInstances = $request->user()->currentTeam->instances->pluck('id');

        $instances = Instance::with([
            'template',
            'template.category',
            'location',
        ])->whereIn('id', $teamInstances)->get();

        return [
            "slideOver" => 'Objects/ObjectsIndex',
            "props" => [
                "instances" => $instances,
                "links" => $request->user()->currentTeam->links
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

        return Inertia::render('Objects/Objects', ['instances' => $instances]);
    }

    public function markers(Request $request)
    {
        $teamInstances = $request->user()->currentTeam->instances->pluck('id');

        $instances = Instance::with([
            'template',
            'template.category',
            'location',
        ])->whereIn('id', $teamInstances)->get();

        return [
            "instances" => $instances
        ];
    }
}
