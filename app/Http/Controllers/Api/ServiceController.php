<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ServiceController extends Controller
{
    public function url(Request $request)
    {
        $data = [
            'url' => '',
        ];

        $service_switch = getConfig('service', 'switch');

        if (!$service_switch) {
            return Response::jsonSuccess('客服开关关闭！', $data);
        }

        $data['url'] = getConfig('service', 'url');

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
