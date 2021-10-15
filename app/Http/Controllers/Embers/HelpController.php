<?php

namespace App\Http\Controllers\Embers;

use App\Contracts\Embers\Help\IndexesHelp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class HelpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        [$faqs, $faqIdToFocus] = app(IndexesHelp::class)->index($request->user(), $request->all());

        return Inertia::render('Help/HelpIndex', [
            'faqs' => $faqs,
            'faqToFocus' => $faqIdToFocus
        ]);
    }
}
