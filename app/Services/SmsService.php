<?php

namespace App\Services;

use App\Traits\CacheTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SmsService
{
    use CacheTrait;

    /**
     * 发送验证码
     *
     * @param  $area
     * @param  $mobile
     *
     * @return string
     * @throws \Exception
     */
    public function send($area, $mobile)
    {
        // configs
        $url = config('api.sms.url');
        $message = config('api.sms.message');
        $token = config('api.sms.token');
        $product = config('api.sms.product');

        $code = random_int(1000, 9999);
        $this->saveVerifyCode($mobile, $area, $code);
        $content = urlencode(preg_replace('{{code}}', $code, $message));
        $time = time();

        // 短信参数
        $params = [
            'areaCode' => $area,
            'mobile' => $mobile,
            'msg' => $content,
            'token' => md5($time . $token. $area . $mobile . $content),
            'timestamp' => time(),
            'proCode' => $product,
        ];

        // 写日志
        Log::notice(sprintf('短信发送至手机号：%s-%s', $params['areaCode'], $params['mobile']));

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/x-www-form-urlencoded']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
            $result = curl_exec($curl);
            curl_close($curl);

        } catch (\Exception $e) {
            return sprintf('短信发送失败：短信平台无回应，手机号：%s-%s, %s', $params['areaCode'], $params['mobile'], $e->getMessage());
        }

        Log::notice(sprintf('短信发送至手机号：%s-%s, 结果：%s', $params['areaCode'], $params['mobile'], $result));

        $result = json_decode($result, true);

        if ($result['code'] != 200) {
            return sprintf('短信发送失败：%s，手机号：%s-%s', $result['msg'] , $params['areaCode'], $params['mobile']);
        }

        return '';
    }

    /**
     * 储存验证码
     *
     * @param $mobile
     * @param $area
     * @param $code
     */
    public function saveVerifyCode($mobile, $area, $code)
    {
        $cache_key = $this->getCacheKeyPrefix() . sprintf('sms:%s-%s:code', $area, $mobile);

        Cache::put($cache_key, $code);
    }

    /**
     * 检查短信验证码是否正确
     *
     * @param $mobile
     * @param $area
     * @param $code
     *
     * @return bool
     */
    public function isVerifyCode($mobile, $area, $code): bool
    {
        $cache_key = $this->getCacheKeyPrefix() . sprintf('sms:%s-%s:code', $area, $mobile);

        $cache_code = Cache::get($cache_key);

        if ($cache_code == $code) {
            return true;
        }

        return false;
    }

    /**
     * 限制每天发送次数
     *
     * @param $area
     * @param $mobile
     *
     * @return bool
     */
    public function limitTodayFrequency($area, $mobile): bool
    {
        $cache_key = $this->getCacheKeyPrefix() . sprintf('sms:%s-%s:today', $area, $mobile);

        $today_request = Cache::get($cache_key);

        if (!$today_request) {
            $cache_ttl = mktime(24,0,0) - time(); // 今天结束前秒数
            Cache::put($cache_key, 1, $cache_ttl);
            return true;
        }

        if ($today_request <= 15) {
            Cache::increment($cache_key);
            return true;
        }

        return false;
    }

    /**
     * 限制手机号发送频率
     *
     * @param $area
     * @param $mobile
     *
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function limitMobileFrequency($area, $mobile): bool
    {
        $cache_key = $this->getCacheKeyPrefix() . sprintf('sms:%s-%s:protect', $area, $mobile);

        $protect = Cache::get($cache_key);

        if (!$protect) {
            Cache::put($cache_key, true, 30);
            return true;
        }

        return false;
    }

    /**
     * 限制IP发送频率
     *
     * @param $ip
     *
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function limitIpFrequency($ip): bool
    {
        $cache_key = $this->getCacheKeyPrefix() . sprintf('sms:ip:%s', $ip);

        $protect = Cache::get($cache_key);

        if (!$protect) {
            Cache::put($cache_key, true, 30);
            return true;
        }

        return false;
    }
}
