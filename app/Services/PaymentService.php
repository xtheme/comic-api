<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function getAccountParams(Request $request): array
    {
        // todo change config
        $pay_game_id = getOldConfig('web_config', 'pay_game_id');

        $user = $request->user;

        // 需要加密的用户数据
        $info = [
            'uuid'     => $user->device_id,
            'ip'       => $user->last_login_ip,
            'channel'  => $user->platform,
            'uid'      => $user->id,
            'nickname' => $user->username,
            'gameId'   => $pay_game_id,
        ];

        // 请求参数
        $data = [
            'gameId' => $pay_game_id,
            'param' => [
                'f' => $this->getEncryptString($info), // 需要加密的用户数据
                'timestamp' => (string) time()
            ],
            'optName' => 'system',
            'ipAddr' => $request->header('ip'),
        ];

        $data['sign'] = $this->getSign($data['param']);

        return $data;
    }

    public function getTransferParams(Request $request): array
    {
        // todo change config
        $pay_game_id = getOldConfig('web_config', 'pay_game_id');

        $data = [
            'gameId'  => $pay_game_id,
            'param'   => [
                'customerId'  => $request->post('customer_id'),
                'referenceId' => $request->post('order_id'),
                'gameId'      => $pay_game_id,
                'amount'      => '30.00',
                'opType'      => '0',
                'timestamp'   => (string) time(),
            ],
            'optName' => 'system',
            'ipAddr'  => $request->header('ip'),
        ];

        $data['sign'] = $this->getSign($data['param']);

        return $data;
    }

    /**
     * 在金流平台注册并获取 uid
     *
     * @param  Request  $request
     *
     * @return bool
     */
    public function createAccount(Request $request)
    {
        // todo change config
        $pay_register_url = getOldConfig('web_config', 'pay_register_url');

        $data = $this->getAccountParams($request);

        $json_str = json_encode($data);
        // Log::debug($json_str);

        $response = $this->requestApi($pay_register_url, $json_str);

        if (isset($response['code']) && $response['code'] == '200') {
            return true;
        }

        Log::error(sprintf('金流方注册失败: (%s) %s', $response['code'], $response['msg']));

        return false;
    }

    /**
     * 用户数据加密
     *
     * @param $param
     *
     * @return string
     */
    public function getEncryptString($param)
    {
        $str = http_build_query($param);

        $method = 'AES-128-CBC';
        // todo change config
        $key = getOldConfig('web_config', 'pay_aes_key');
        $iv = getOldConfig('web_config', 'pay_aes_iv');
        // $key = getConfig('payment', 'aes_key');
        // $iv = getConfig('payment', 'aes_iv');

        $aes = new AesService($method, $key, $iv);
        $str = $aes->encrypt($str);

        return $str;
    }

    /**
     * 生成请求签名
     *
     * @param $param
     *
     * @return string
     */
    public function getSign($param)
    {
        // todo change config
        $sign_key = getOldConfig('web_config', 'pay_sign_key');
        // Log::debug('pay_sign_key: ' . $sign_key);
        ksort($param);
        $str = md5(json_encode($param));
        $sign = md5($str . $sign_key);
        return $sign;
    }

    /**
     * 请求金流平台
     *
     * @param $param
     *
     * @return bool|string
     */
    public function requestApi($url, $json_str)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $json_str,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        Log::debug('requestApi: ' . $response);
        curl_close($curl);

        return json_decode($response, true);
    }
}