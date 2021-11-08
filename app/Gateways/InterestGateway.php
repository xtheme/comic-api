<?php

namespace App\Gateways;

use App\Models\Domain;
use App\Models\Order;
use App\Models\Pricing;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class InterestGateway extends BaseGateway implements Contracts\GatewayInterface
{
    const PAY_URL = 'https://www.qupay88.com/Pay';

    // todo only support wap
    public function getBackUrl($platform)
    {
        $domain = Domain::where('type', $platform)->where('status', 1)->get();

        if (!$domain) {
            return '';
        }

        return $domain->domain . '/record-order';
    }

    // 獲取支付網址
    public function pay(Pricing $plan)
    {
        $order = $this->createOrder($plan);

        $data = [
            'fxid' => $this->app_id, // 商务号
            'fxddh' => $order->order_no, // 商户订单号
            'fxdesc' => '', // 商品名称, utf-8编码
            'fxfee' => $plan->price, // 支付金额,单位元
            'fxnotifyurl' => route('api.payment.notify', $order->order_no), // 异步接收支付结果通知的回调地址，不能携带参数
            'fxbackurl' => $this->getBackUrl($order->platform), // 同步通知地址, 支付成功后跳转到的地址
            'fxpay' => 'wxwap', // 请求支付的接口类型
            'fxattch' => '', // 备注, utf-8编码
            'fxip' => request()->ip(), // 用户支付时设备的IP地址
            'fxuserid' => $order->user_id, // 商户自定义客户号
        ];

        $data = array_merge($data, $this->pay_options);

        $data['fxsign'] = $this->getSign($data);

        // todo need check
        $response = $this->postJson(self::PAY_URL, $data);

        if ($response['status'] != 1) {
            throw new \Exception($response['error']);
        }

        // 判斷要返回哪個網址
        $pay_url = $response['payurl'];

        return [
            'order_no' => $order->order_no,
            'pay_url' => $pay_url,
        ];
    }

    // 簽名公式
    public function getSign(array $params)
    {
        $data = [
            'fxid' => $params['fxid'],
            'fxddh' => $params['fxddh'],
            'fxfee' => $params['fxfee'],
            'fxnotifyurl' => $params['fxnotifyurl'],
            'app_key' => $this->app_key,
        ];

        $str = join('', $data);
        $sign = md5($str);

        // ksort($params);
        // $str = urldecode(http_build_query($params));
        // $sign = md5($str . '&key=' . $this->app_key);
        // $sign = strtoupper($sign);

        return $sign;
    }

    // 第三方回調上分時驗證簽名
    public function checkSign($params)
    {
        $callback_sign = $params['fxsign'];

        if ($callback_sign == $this->getSign($params)) {
            return true;
        }

        return false;
    }

    // 回調成功更新訂單
    public function updateOrder(Order $order, array $params)
    {
        // 獲取渠道訂單號
        $transaction_id = $params['fxorder'];

        if ($params['fxstatus'] != 1) {
            return 'fail';
        }

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
            'fxid' => $this->app_id, // 商务号
            'fxddh' => $order->order_no, // 商户订单号
            'fxdesc' => '', // 商品名称, utf-8编码
            'fxfee' => $order->amount, // 支付金额,单位元
            'fxnotifyurl' => route('api.payment.notify', $order->order_no), // 异步接收支付结果通知的回调地址，不能携带参数
            'fxbackurl' => $this->getBackUrl($order->platform), // 同步通知地址, 支付成功后跳转到的地址
            'fxpay' => 'wxwap', // 请求支付的接口类型
            'fxattch' => '', // 备注, utf-8编码
            'fxip' => request()->ip(), // 用户支付时设备的IP地址
            'fxuserid' => $order->user_id, // 商户自定义客户号
        ];

        $data['fxsign'] = $this->getSign($data);

        return $data;
    }
}
