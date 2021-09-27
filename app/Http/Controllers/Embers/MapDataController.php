<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\MapData\IndexesMapData;
use App\Contracts\Embers\MapData\StoresMapData;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MapDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $mapData = app(IndexesMapData::class)->index($request->user());

        return response()->json($mapData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        app(StoresMapData::class)->store($request->user(), $request->all());

        return redirect()->route('map-data.store');
    }
}
