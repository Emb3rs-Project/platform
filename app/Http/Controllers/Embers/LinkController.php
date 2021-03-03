<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LinkController extends Controller
{
    public function index()
    {
        return Inertia::render('Objects/Links/LinkIndex');
    }
}
