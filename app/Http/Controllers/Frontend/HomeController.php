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
        return redirect('login');
        // $data = [
        //     'locations' => Location::all(),
        //     'shipments' => Shipment::all(),
        // ];
        //
        // return view('frontend.home')->with($data);
    }

    // 試算運費
    public function trialCalculation()
    {
    }

    // 儲存委託單
    public function storeRequisition()
    {
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
