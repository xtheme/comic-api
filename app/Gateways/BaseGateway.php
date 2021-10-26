<?php

namespace App\Gateways;

use App\Models\Order;
use App\Models\Pricing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BaseGateway
{
    public $payment_id;
    public $app_id;
    public $app_key;
    public $pay_options;

    public function init(array $params)
    {
        $this->payment_id = $params['payment_id'];
        $this->app_id = $params['app_id'];
        $this->app_key = $params['app_key'];
        $this->pay_options = $params['pay_options'];
    }

    public function postJson($url, $data)
    {
        $response = Http::acceptJson()->post($url, $data);

        return $response->json();
    }

    public function createOrder(Pricing $plan)
    {
        $count = Order::whereDate('created_at', date('Y-m-d'))->count();

        $order_no = date('ymd') . str_pad((string) ($count + 1), 5, '0', STR_PAD_LEFT) . rand(10, 99);

        $plan_options = [
            'coin' => $plan->coin,
            'gift_coin' => $plan->gift_coin,
            'days' => $plan->days,
            'gift_days' => $plan->gift_days,
        ];

        $data = [
            'order_no' => $order_no,
            'user_id' => auth('sanctum')->user()->id,
            'app_id' => auth('sanctum')->user()->app_id,
            'channel_id' => auth('sanctum')->user()->channel_id,
            'type' => $plan->type,
            'amount' => $plan->price,
            'currency' => 'CNY',
            'plan_options' => $plan_options,
            'payment_id' => $this->payment_id,
            'ip' => request()->ip(),
            'platform' => request()->header('platform'),
        ];

        $order = Order::create($data);

        return $order;
    }

}
