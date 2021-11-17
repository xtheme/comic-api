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
    function getOptions()
    {
        $options = new Config();
        $options->protocol = 'https';
        $options->gatewayHost = 'openapi.alipay.com';
        $options->signType = 'RSA2';

        $options->appId = config('pay.alipay.app_id');
        $options->merchantPrivateKey = config('pay.alipay.app_secret_cert'); // 私钥
        $options->alipayPublicKey = config('pay.alipay.app_public_cert_path'); // 支付宝公钥

        return $options;
    }

    // 獲取支付網址
    public function pay(Pricing $plan): array
    {
        $order = $this->createOrder($plan);

        // 1. 设置参数（全局只需设置一次）
        Factory::setOptions($this->getOptions());

        try {
            // 2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $subject = $plan->name;                                     // 订单标题。注意：不可使用特殊字符，如 /，=，& 等。
            $outTradeNo = $order->order_no;                             // 商户网站唯一订单号
            $totalAmount = $plan->price;                                // 订单总金额
            $quitUrl = $order->domain . '/deposit';                     // 用户付款中途退出返回商户网站的地址
            $returnUrl = $order->domain . '/record-order';
            $notifyUrl = route('api.payment.notify', $order->order_no); // 异步通知地址

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
            'pay_form' => $result->body,
        ];
    }

    public function getSign(array $params)
    {
    }

    // 第三方回調上分時驗證簽名
    public function checkSign($params): bool
    {
        return Factory::payment()->common()->verifyNotify($params);
    }

    // 回調成功更新訂單
    public function updateOrder(Order $order, array $params): string
    {
        // 獲取渠道訂單號
        $transaction_id = $params['trade_no'];

        if ($params['trade_status'] == 'TRADE_SUCCESS') {
            DB::transaction(function () use ($order, $transaction_id) {
                app(UserService::class)->updateOrder($order, $transaction_id);
            });
        }

        // 返回三方指定格式
        return 'success';
    }

    // 模擬回調數據
    public function mockCallback(Order $order)
    {
    }
}
