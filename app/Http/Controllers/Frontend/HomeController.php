<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Locations;
use App\Models\Shipment;

class HomeController extends Controller
{
    // index
    public function index()
    {
        $data = [
            'locations' => Locations::all(),
            'shipments' => Shipment::all(),
        ];

        return view('frontend.home')->with($data);
    }

    // 試算運費
    public function trialCalculation()
    {
    }

    // 儲存委託單
    public function storeRequisition()
    {
    }
}
