<?php

namespace App\Gateways;

use App\Models\Order;
use App\Models\Pricing;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LampGateway extends BaseGateway implements Contracts\GatewayInterface
{
    // 獲取支付網址
    public function pay(Pricing $plan): array
    {
        $order = $this->createOrder($plan);

        $data = [
            'mch_id' => $this->app_id,                                       // 商务号
            'out_order_no' => $order->order_no,                              // 商户订单号
            'amount' => $plan->price,                                        // 支付金额,单位元
            'channel_code' => 'wechatPartnerApplets',                        // 通道code
            'notify_url' => route('api.payment.notify', $order->order_no),   // 异步接收支付结果通知的回调地址，不能携带参数
            'return_url' => $order->domain . '/record-order',                // 同步通知地址, 支付成功后跳转到的地址
            'client_ip' => request()->ip(),                                  // 用户支付时设备的IP地址
            'timestamp' => time(),                                           // unix秒级时间戳
            'version' => 'v1.0',                                             // 接口版本
        ];

        $data = array_merge($data, $this->pay_options);

        $data['sign'] = $this->getSign($data);

        // Post as form
        $response = Http::asForm()->post($this->api_url . '/gateway/order/unified', $data);
        $result = $response->json();

        Log::debug('LampGateway', $result);

        if ($result['code'] != 0) {
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
        unset($params['sign']);

        foreach ($params as $key => $value) {
            if (!$value) {
                unset($params[$key]);
            }
        }
        ksort($params);
        $str = urldecode(http_build_query($params));
        $sign = md5($str . $this->app_key);

        return $sign;
    }

    // 第三方回調上分時驗證簽名
    public function checkSign($params): bool
    {
        $callback_sign = $params['sign'];

        if ($callback_sign == $this->getSign($params)) {
            return true;
        }

        return false;
    }

    // 回調成功更新訂單
    public function updateOrder(Order $order, array $params): string
    {
        // 獲取渠道訂單號
        $transaction_id = $params['order_no'];

        if ($params['status'] != 2) {
            return 'fail';
        }

        DB::transaction(function () use ($order, $transaction_id) {
            app(UserService::class)->updateOrder($order, $transaction_id);
        });

        // 返回三方指定格式
        return 'success';
    }

    // 模擬回調數據
    public function mockCallback(Order $order): array
    {
        $data = [
            'mch_id' => $this->app_id,                                       // 商务号
            'order_no' => $order->order_no,                                  // 系统订单号
            'out_order_no' => $order->order_no,                              // 商户订单号
            'amount' => $order->amount,                                      // 支付金额,单位元
            'client_ip' => request()->ip(),                                  // 用户支付时设备的IP地址
            'status' => '2',                                                 // 代付状态{2-处理成功 3-处理失败}
            'resolved_timestamp' => time(),                                  // unix时间戳 确认时间
            'created_timestamp' => time(),                                   // unix时间戳 创建时间
        ];

        $data['sign'] = $this->getSign($data);

        return $data;
    }
}
