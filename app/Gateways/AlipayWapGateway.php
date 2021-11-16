<?php

namespace App\Gateways;

use App\Models\Order;
use App\Models\Pricing;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Alipay\EasySDK\Kernel\Factory;
use Alipay\EasySDK\Kernel\Util\ResponseChecker;
use Alipay\EasySDK\Kernel\Config;
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

        // 为避免私钥随源码泄露，推荐从文件中读取私钥字符串而不是写入源码中
        $options->merchantPrivateKey = config('pay.alipay.app_secret_cert');

        // $options->alipayCertPath = '<-- 请填写您的支付宝公钥证书文件路径，例如：/foo/alipayCertPublicKey_RSA2.crt -->';
        // $options->alipayRootCertPath = '<-- 请填写您的支付宝根证书文件路径，例如：/foo/alipayRootCert.crt" -->';
        // $options->merchantCertPath = '<-- 请填写您的应用公钥证书文件路径，例如：/foo/appCertPublicKey_2019051064521003.crt -->';

        //注：如果采用非证书模式，则无需赋值上面的三个证书路径，改为赋值如下的支付宝公钥字符串即可
        $options->alipayPublicKey = config('pay.alipay.app_public_cert_path');

        //可设置异步通知接收服务地址（可选）
        // $options->notifyUrl = "<-- 请填写您的支付类接口异步通知接收服务地址，例如：https://www.test.com/callback -->";

        //可设置AES密钥，调用AES加解密相关接口时需要（可选）
        // $options->encryptKey = "<-- 请填写您的AES密钥，例如：aa4BtZ4tspm2wnXLb1ThQA== -->";

        return $options;
    }

    // 獲取支付網址
    public function pay(Pricing $plan): array
    {
        $order = $this->createOrder($plan);

        $data = [
            'fxid' => $this->app_id,                                        // 商务号
            'fxddh' => $order->order_no,                                    // 商户订单号
            'fxdesc' => '',                                                 // 商品名称, utf-8编码
            'fxfee' => $plan->price,                                        // 支付金额,单位元
            'fxnotifyurl' => route('api.payment.notify', $order->order_no), // 异步接收支付结果通知的回调地址，不能携带参数
            'fxbackurl' => $order->domain . '/record-order',                // 同步通知地址, 支付成功后跳转到的地址
            'fxpay' => 'wxwap',                                             // 请求支付的接口类型
            'fxattch' => '',                                                // 备注, utf-8编码
            'fxip' => request()->ip(),                                      // 用户支付时设备的IP地址
            'fxuserid' => $order->user_id,                                  // 商户自定义客户号
        ];

        // 1. 设置参数（全局只需设置一次）
        Factory::setOptions($this->getOptions());

        try {
            // 2. 发起API调用（以支付能力下的统一收单交易创建接口为例）
            $subject = '';      // 订单标题。注意：不可使用特殊字符，如 /，=，& 等。
            $outTradeNo = '';   // 商户网站唯一订单号
            $totalAmount = '';  // 订单总金额
            $quitUrl = '';      // 用户付款中途退出返回商户网站的地址
            $returnUrl = '';
            $notifyUrl = ''; // 异步通知地址

            $result = Factory::payment()->wap()->asyncNotify($notifyUrl)->pay($subject, $outTradeNo, $totalAmount, $quitUrl, $returnUrl);
            $responseChecker = new ResponseChecker();
            // 3. 处理响应或异常
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

    // 簽名公式
    public function getPaySign(array $params): string
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

        return $sign;
    }

    // 异步通知簽名公式
    public function getSign(array $params): string
    {
        $data = [
            'fxstatus' => $params['fxstatus'],
            'fxid' => $params['fxid'],
            'fxddh' => $params['fxddh'],
            'fxfee' => $params['fxfee'],
            'app_key' => $this->app_key,
        ];

        $str = join('', $data);
        $sign = md5($str);

        return $sign;
    }

    // 第三方回調上分時驗證簽名
    public function checkSign($params): bool
    {
        $callback_sign = $params['fxsign'];

        if ($callback_sign == $this->getSign($params)) {
            return true;
        }

        return false;
    }

    // 回調成功更新訂單
    public function updateOrder(Order $order, array $params): string
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
    public function mockCallback(Order $order): string
    {
        $data = [
            'fxid' => $this->app_id,            // 商务号
            'fxddh' => $order->order_no,        // 商户订单号
            'fxorder' => '1457768687644704768', // 渠道訂單號
            'fxdesc' => '',                     // 商品名称, utf-8编码
            'fxfee' => $order->amount,          // 支付金额,单位元
            'fxattch' => '',                    // 备注, utf-8编码
            'fxstatus' => 1,                    // 支付成功
            'fxtime' => time(),                 // 支付成功时的时间，格式unix时间戳
        ];

        $data['fxsign'] = $this->getSign($data);

        $str = '';

        foreach ($data as $key => $val) {
            $str .= $key . ':' . $val . "\r";
        }

        return $str;
    }
}
