<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    // index
    public function index()
    {
        return view('frontend.home');
    }
}
