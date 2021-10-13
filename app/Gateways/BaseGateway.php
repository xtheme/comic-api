<?php

namespace App\Gateways;

use Illuminate\Support\Facades\Http;

class BaseGateway
{
    public $app_id;
    public $app_key;
    public $pay_options;

    public function init(array $params)
    {
        $this->gateway_id = $params['gateway_id'];
        $this->app_id = $params['app_id'];
        $this->app_key = $params['app_key'];
        $this->pay_options = $params['pay_options'];
    }

    public function postJson($url, $data)
    {
        $response = Http::acceptJson()->post($url, $data);

        return $response->json();
    }
}
