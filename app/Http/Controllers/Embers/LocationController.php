<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index()
    {
        return Inertia::render('Objects/Locations/LocationIndex');
    }

    public function create()
    {
        return Inertia::render('Objects/Locations/LocationCreate');
    }

    public function store(Request $request)
    {
        var_dump($request);
    }
}
