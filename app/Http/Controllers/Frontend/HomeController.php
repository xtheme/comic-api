<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    // index
    public function index()
    {
        return '';
    }

    public function noPermission()
    {
        return view('errors.403');
    }

    public function notFound()
    {
        return view('errors.404');
    }

    public function internalError()
    {
        return view('errors.500');
    }
}
