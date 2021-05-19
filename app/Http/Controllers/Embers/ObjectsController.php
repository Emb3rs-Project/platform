<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Instance;
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
            'location.geoObject'
        ])->whereIn('id', $teamInstances)->get();

        return [
            "slideOver" => 'Objects/ObjectsIndex',
            "props" => [
                // TODO: include Links
                "instances" => $instances
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
            'location.geoObject'
        ])->whereIn('id', $teamInstances)->get();

        return Inertia::render('Objects/Objects', ['instances' => $instances]);
    }
}
