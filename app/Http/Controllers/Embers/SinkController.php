<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SinkController extends Controller
{
    public function index()
    {
        return Inertia::render('Objects/Sinks/SinkIndex');
    }
}
