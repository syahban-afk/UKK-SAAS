<?php

namespace App\Http\Controllers;

use App\Models\pakets_model;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $featuredMenus = pakets_model::take(3)->get();
        return view('welcome', compact('featuredMenus'));
    }
}
