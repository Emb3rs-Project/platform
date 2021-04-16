<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use App\Models\Instance;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ObjectsController extends Controller
{
    public function index(): Response
    {
        $currentTeamInstances = Auth::user()->currentTeam->instances->pluck('id');

        $instances = Instance::with([
            'template',
            'template.category',
            'location',
            'location.geoObject'
        ])->whereIn('id', $currentTeamInstances)->get();

        return Inertia::render('Objects/Objects', ['instances' => $instances]);
    }
}
