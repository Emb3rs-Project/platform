<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use App\Models\Link;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ObjectsController extends Controller
{
    public function index()
    {
        $teamInstances = Auth::user()->currentTeam->instances->pluck('id');

        $instances = Instance::with([
            'template',
            'template.category',
            'location',
        ])->whereIn('id', $teamInstances)->get();

        return [
            "slideOver" => 'Objects/ObjectsIndex',
            "props" => [
                // TODO: include Links
                "instances" => $instances,
                "links" => Auth::user()->currentTeam->links
            ]
        ];
    }

    public function map(): Response
    {
        $teamInstances = Auth::user()->currentTeam->instances->pluck('id');

        $instances = Instance::with([
            'template',
            'template.category',
            'location',
        ])->whereIn('id', $teamInstances)->get();

        return Inertia::render('Objects/Objects', ['instances' => $instances]);
    }

    public function markers()
    {
        $teamInstances = Auth::user()->currentTeam->instances->pluck('id');

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
