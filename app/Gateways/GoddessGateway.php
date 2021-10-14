<?php

namespace App\Gateways;

use App\Models\Order;
use App\Models\Pricing;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class GoddessGateway extends BaseGateway implements Contracts\GatewayInterface
{
    const ID_FIELD = 'th_orderno';
    const PAY_URL = 'http://47.75.109.3:8081/gate/take_order.do?';
    // const QUERY_URL = 'http://47.75.109.3:8081/gate/take_order.do?';

    // 獲取支付網址
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

        // return $data;

        $response = $this->postJson(self::PAY_URL, $data);

        if (!$response['result']) {
            throw new \Exception($response['message']);
        }

        // 判斷要返回哪個網址
        $pay_url = $response['data']['pay_url'];

        return [
            'order_no' => $order->order_no,
            'pay_url' => $pay_url,
        ];
    }

    // 簽名公式
    public function getSign(array $params)
    {
        foreach ($params as $key => $value) {
            if (!$value) {
                unset($params[$key]);
            }
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

    // 回調成功更新訂單
    public function updateOrder(Order $order, array $params)
    {
        // 獲取渠道訂單號
        $transaction_id = $params[self::ID_FIELD];

        DB::transaction(function () use ($order, $transaction_id) {
            app(UserService::class)->updateOrder($order, $transaction_id);
        });

        // 返回三方指定格式
        return 'success';
    }

    // 模擬回調數據
    public function mockCallback(Order $order)
    {
        $data = [
            'channel' => $this->app_id,
            'orderno' => $order->order_no,
            'th_orderno' => $order->order_no,
            'order_money' => $order->amount * 100,
            'payed_money' => $order->amount * 100,
            'notifyurl' => route('api.payment.callback', ['order_no' => $order->order_no]),
        ];

        $data['sign'] = $this->getSign($data);

        return $data;
    }
}
