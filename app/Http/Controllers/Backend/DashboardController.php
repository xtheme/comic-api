<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // index
    public function index()
    {
        return view('backend.index');
    }

    public function dashboard()
    {
        return view('backend.dashboard');
    }
}
