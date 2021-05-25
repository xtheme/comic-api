<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PricingController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->user->orders->where('status', 1)->count() ? 0 : 1;

        $data = PricingPackage::where('status', $status)->orderByDesc('sort')->get();

        return Response::jsonSuccess('返回成功', $data);
    }
}
