<?php

namespace App\Http\Controllers;

use App\Models\EdoLanguage;
use App\Models\EdoCuisine;
use App\Models\TraditionalAttire;

class CultureController extends Controller
{
    public function index()
    {
        $languages = EdoLanguage::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $cuisines = EdoCuisine::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $attires = TraditionalAttire::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('ethnic_group')
            ->get()
            ->groupBy('ethnic_group');

        return view('culture', compact('languages', 'cuisines', 'attires'));
    }
}