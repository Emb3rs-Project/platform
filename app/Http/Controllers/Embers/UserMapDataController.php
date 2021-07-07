<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Users\IndexesUsersMapData;
use App\Contracts\Embers\Users\StoresUsersMapData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class UserMapDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mapData = app(IndexesUsersMapData::class)->index($request->user());

        return response()->json($mapData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        app(StoresUsersMapData::class)->store($request->user(), $request->all());

        return Redirect::route('user.mapData.index');
    }
}
