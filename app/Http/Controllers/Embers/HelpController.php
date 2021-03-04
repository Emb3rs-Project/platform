<?php

namespace App\Http\Controllers\Embers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HelpController extends Controller
{
    public function index()
    {
        return Inertia::render('Help/HelpIndex');
    }
}
