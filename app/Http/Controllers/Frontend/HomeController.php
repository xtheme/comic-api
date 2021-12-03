<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // index
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('backend');
        }

        if (config('app.env') != 'production') {
            return redirect()->route('login');
        }

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

    public function location(Request $request)
    {
        return $request->header('CF-IPCountry');
    }
}
