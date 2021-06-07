<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        // todo change config
        $service_sign_secret = getOldConfig('web_config', 'service_sign_secret');

        return md5(implode('', $params) . $service_sign_secret);
    }

    /**
     * 查询廣告位底下的廣告列表
     */
    public function url()
    {
        $data = [
            'url' => ''
        ];

        // todo change config
        $service_switch = getOldConfig('web_config', 'service_switch');

        if (!$service_switch) {
            return $this->jsonSuccess('客服开关关闭！', $data);
        }

        // todo change config
        $service_app_id = getOldConfig('web_config', 'service_app_id');
        $service_app_secret = getOldConfig('web_config', 'service_app_secret');
        $service_open_id = getOldConfig('web_config', 'service_open_id');
        $service_api_url= getOldConfig('web_config', 'service_api_url');

        $params = [
            'app_id'     => $service_app_id,
            'app_secret' => $service_app_secret,
            'open_id'    => $service_open_id,
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
