<?php

namespace App\Gateways;

use App\Models\Payment;
use App\Models\Pricing;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoddessGateway implements GatewayInterface
{
    public $app_id;
    public $app_key;
    public $pay_options;

    const PAY_URL = 'http://47.75.109.3:8081/gate/take_order.do?';

    public function init(array $params)
    {
        $this->gateway_id = $params['gateway_id'];
        $this->app_id = $params['app_id'];
        $this->app_key = $params['app_key'];
        $this->pay_options = $params['pay_options'];
    }

    public function pay(Pricing $plan)
    {
        $payment_service = app(PaymentService::class);

        $order = $payment_service->createOrder($plan, $this->gateway_id);

        $data = [
            'channel' => $this->app_id,
            'type' => '',
            'money' => (int) $plan->price * 100, // 單位分
            'orderno' => $order->order_no,
            'notifyurl' => route('api.payment.callback', ['order_no' => $order->order_no]),
        ];

        $data = array_merge($data, $this->pay_options);

        $data['sign'] = $this->getSign($data);

        return $data;

        // $response = $this->requestApi(self::PAY_URL, $data);
        //
        // if (!$response['result']) {
        //     throw new \Exception($response['message']);
        // }
        //
        // // 判斷要返回哪個網址
        // $pay_url = $response['data']['pay_url'];
        //
        // if ($this->payment->button_target == 'iframe') {
        //     $pay_url = $response['data']['qr_url'];
        // }
        //
        // return [
        //     'order_no' => $order->order_no,
        //     'target' => $this->payment->button_target,
        //     'pay_url' => $pay_url,
        // ];
    }

    public function getSign(array $params)
    {
        foreach($params as $key => $value) {
            if (!$value) unset($params[$key]);
        }
        ksort($params);
        $str = urldecode(http_build_query($params));
        $sign = md5($str . '&key=' . $this->app_key);
        $sign = strtoupper($sign);

        return $sign;
    }

    // 第三方回調上分時驗證簽名
    public function checkSign($params)
    {
        $callback_sign = $params['sign'];

        unset($params['sign']);

        if ($callback_sign == $this->getSign($params)) {
            return true;
        }

        return false;
    }

    public function requestApi($url, $data)
    {
        $response = Http::acceptJson()->post($url, $data);

        return $response->json();
    }

}