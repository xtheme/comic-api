<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Support\Facades\Response;

class BootstrapController extends Controller
{
    // 前端配置
    public function configs()
    {
        $data = getConfigs('frontend');

        return Response::jsonSuccess(__('api.success'), $data);
    }

    // 公告
    public function notices()
    {
        $data = Notice::whereStatus(1)->get()->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
