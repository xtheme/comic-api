<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Pricing;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class PaymentService
{
    private $payment;
    private $third_party;

    public function init(Payment $payment)
    {
        $this->payment = $payment;

        $sdk = $this->payment->sdk;

        $this->third_party = app($sdk);

        $this->third_party->init([
            'gateway_id' => $this->payment->id,
            'app_id' => $this->payment->app_id,
            'app_key' => $this->payment->app_key,
            'pay_options' => $this->payment->pay_options,
        ]);

        return $this;
    }

    public function pay(Pricing $plan)
    {
        return $this->third_party->pay($plan);
    }

    public function callback(Order $order, array $params)
    {
        $valid = $this->third_party->checkSign($params);

        if (!$valid) {
            return Response::jsonError('签名验证失败！');
        }

        return $this->third_party->updateOrder($order, $params);
    }

    public function createOrder(Pricing $plan, $payment_id)
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
            'user_id' => Auth::user()->id,
            'type' => $plan->type,
            'amount' => $plan->price,
            'currency' => 'CNY',
            'plan_options' => $plan_options,
            'payment_id' => $payment_id,
            'ip' => request()->ip(),
            'platform' => request()->header('platform'),
        ];

        $order = Order::create($data);

        return $order;
    }
}