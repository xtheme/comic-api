<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ServiceController extends Controller
{
    private function genToken($params)
    {
        return md5($params['app_id'] . $params['app_secret'] . $params['open_id'] . $params['time']);
    }

    private function genSign($params)
    {
        ksort($params);

        $service_sign_secret = getConfig('service', 'sign_secret');


        return md5(implode('', $params) . $service_sign_secret);
    }

    /**
     * 查询廣告位底下的廣告列表
     */
    public function url(Request $request)
    {
        $data = [
            'url' => ''
        ];

        $service_switch = getConfig('service', 'switch');

        if (!$service_switch) {
            return $this->jsonSuccess('客服开关关闭！', $data);
        }

        $service_app_id = getConfig('service', 'app_id');
        $service_app_secret = getConfig('service', 'app_secret');
        $service_api_url= getConfig('service', 'api_url');

        $params = [
            'app_id'     => $service_app_id,
            'app_secret' => $service_app_secret,
            'open_id'    => $request->user()->id,
            'time'       => time(),
        ];

        $params['sign'] = $this->genSign($params);
        $params['token'] = $this->genToken($params);

        $data['url'] = sprintf('%s/#/index?appId=%s&token=%s&openId=%s&sign=%s&time=%s',
            $service_api_url,
            $params['app_id'],
            $params['token'],
            $params['open_id'],
            $params['sign'],
            $params['time']
        );

        return Response::jsonSuccess('返回成功', $data);
    }
}
