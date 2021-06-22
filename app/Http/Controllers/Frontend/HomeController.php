<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Shipment;

class HomeController extends Controller
{
    // index
    public function index()
    {
        return '<a href="http://zx9.app">zx9.app</a>';
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
