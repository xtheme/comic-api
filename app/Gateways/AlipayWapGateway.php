<?php

namespace App\Gateways;

use Alipay\EasySDK\Kernel\Config;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use App\Models\Order;
use App\Models\Pricing;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlipayWapGateway extends BaseGateway implements Contracts\GatewayInterface
{
    const FAIL = 'fail';
    const SUCCESS = 'success';

    function getOptions()
    {
        $url = parse_url($this->api_url);

        $options = new Config();
        $options->protocol = $url['scheme'] ?? '';
        $options->gatewayHost = $url['host'] ?? '';
        $options->signType = 'RSA2';

        $options->appId = $this->app_id;
        $options->merchantPrivateKey = config('pay.alipay.app_secret_cert'); // 私钥
        $options->alipayPublicKey = config('pay.alipay.app_public_cert'); // 支付宝公钥

        return $options;
    }

    // 獲取支付網址
    public function pay(Pricing $plan): array
    {
        $order = $this->createOrder($plan);

        // 1. 设置参数（全局只需设置一次）
        Factory::setOptions($this->getOptions());

        try {
            $subject = $plan->name;         // 订单标题。注意：不可使用特殊字符，如 /，=，& 等。
            $outTradeNo = $order->order_no; // 商户网站唯一订单号
            $totalAmount = $plan->price;    // 订单总金额

            $replace = ['{domain}'];
            $with = [$order->domain];
            // 用户付款中途退出返回商户网站的地址
            $quitUrl = str_replace($replace, $with, $this->pay_options['quit_url']); // {domain}/deposit
            // 付款成功後轉跳的網址
            $returnUrl = str_replace($replace, $with, $this->pay_options['return_url']);  // {domain}/record-order
            // 异步通知地址
            $notifyUrl = route('api.payment.notify', $order->order_no);

            // 2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $result = Factory::payment()->wap()->asyncNotify($notifyUrl)->pay($subject, $outTradeNo, $totalAmount, $quitUrl, $returnUrl);

            // 3. 处理响应或异常
            $responseChecker = new ResponseChecker();
            if (!$responseChecker->success($result)) {
                $responseNode = 'alipay_trade_page_pay_response';
                Log::error(sprintf('Alipay 调用失败，原因：%s.%s', $result->$responseNode->msg, $result->$responseNode->sub_msg));
                throw new \Exception($result->$responseNode->msg);
            }
        } catch (\Exception $e) {
            Log::error(sprintf('Alipay 调用失败，原因：%s', $e->getMessage()));
            throw new \Exception($e->getMessage());
        }

        return [
            'order_no' => $order->order_no,
            'pay_url' => $result->body,
        ];
    }

    public function getSign(array $params)
    {
    }

    // 第三方回調上分時驗證簽名
    public function checkSign($params): bool
    {
        // unset($params['sign']);
        // Factory::setOptions($this->getOptions());
        // $result = Factory::payment()->common()->verifyNotify($params);
        // Log::debug($result);

        return true;
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
        // 商户需要验证该通知数据中的 out_trade_no 是否为商户系统中创建的订单号
        if ($order->order_no != $params['out_trade_no']) {
            Log::error($order->order_no . ' order_no 不符', $params);
            return $this->fail();
        }

        // 判断 total_amount 是否确实为该订单的实际金额（即商户订单创建时的金额
        if ($order->amount != $params['total_amount']) {
            Log::error($order->order_no . 'amount 不符', $params);
            return $this->fail();
        }

        // 验证 app_id 是否为该商户本身
        if ($this->app_id != $params['app_id']) {
            Log::error($order->order_no . 'app_id 不符', $params);
            return $this->fail();
        }

        // 獲取渠道訂單號
        $transaction_id = $params['trade_no'];

        if (in_array($params['trade_status'], ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {

            Log::debug('AlipayWapGateway updateOrder $params: ' . $transaction_id, $params);

            DB::transaction(function () use ($order, $transaction_id) {
                app(UserService::class)->updateOrder($order, $transaction_id);
            });

            return $this->success();
        }

        return $this->fail();
    }

    // 模擬回調數據
    public function mockCallback(Order $order)
    {
    }
}
