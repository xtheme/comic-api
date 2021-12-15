<?php

namespace App\Gateways;

use App\Models\Order;
use App\Models\Pricing;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GoddessGateway extends BaseGateway implements Contracts\GatewayInterface
{
    const FAIL = 'fail';
    const SUCCESS = 'success';

    // 獲取支付網址
    public function pay(Pricing $plan): array
    {
        $order = $this->createOrder($plan);

        $data = [
            'channel' => $this->app_id,
            'type' => '',
            'money' => (int) $plan->price * 100, // 單位分
            'orderno' => $order->order_no,
            'notifyurl' => route('api.payment.notify', $order->order_no),
        ];

        $data = array_merge($data, $this->pay_options);

        $data['sign'] = $this->getSign($data);

        // Post Json
        $response = Http::acceptJson()->post($this->api_url, $data);
        $result = $response->json();

        if (!$result['result']) {
            throw new \Exception($result['message']);
        }

        // 判斷要返回哪個網址
        $pay_url = $result['data']['pay_url'];

        return [
            'order_no' => $order->order_no,
            'pay_url' => $pay_url,
        ];
    }

    // 簽名公式
    public function getSign(array $params): string
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
    public function checkSign($params): bool
    {
        $callback_sign = $params['sign'];

        unset($params['sign']);

        if ($callback_sign == $this->getSign($params)) {
            return true;
        }

        return false;
    }

    public function fail(): string
    {
        return self::FAIL;
    }

    public function success(): string
    {
        return self::SUCCESS;
    }

    // 回調成功更新訂單
    public function updateOrder(Order $order, array $params): string
    {
        // 獲取渠道訂單號
        $transaction_id = $params['th_orderno'];

        DB::transaction(function () use ($order, $transaction_id) {
            app(UserService::class)->updateOrder($order, $transaction_id);
        });

        // 返回三方指定格式
        return $this->success();
    }

    // 模擬回調數據
    public function mockCallback(Order $order): array
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
