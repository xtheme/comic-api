<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\MobileRequest;
use App\Services\SmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Sso;

class SmsController extends BaseController
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * 發送驗證碼前檢查使用的電話是否有 SSO
     */
    public function verify(MobileRequest $request)
    {
        $area = $request->input('area');
        $mobile = $request->input('mobile');
        $phone = sprintf('%s-%s', $area, $mobile);

        if (!Sso::checkPhone($phone)) {
            return Response::jsonError('请您先退出旧设备再登录！', 581);
        }

        return $this->send($request);
    }

    /**
     * 單純發送驗證碼
     */
    public function send(MobileRequest $request)
    {
        $area = $request->input('area');
        $mobile = $request->input('mobile');

        // 限制每天发送次数
        if (!$this->smsService->limitTodayFrequency($area, $mobile)) {
            return Response::jsonError('当日短信额度已超出上限，请改天再试！');
        }

        // 限制手机号发送频率
        if (!$this->smsService->limitMobileFrequency($area, $mobile)) {
            return Response::jsonError('发送频率过快，请稍后再试！');
        }

        // 限制IP发送频率
        $ip = $request->ip();
        if (!$this->smsService->limitIpFrequency($ip)) {
            return Response::jsonError('发送频率过快，请稍后再试！');
        }

        // 发送验证码
        $this->smsService->send($area, $mobile);

        // 成功返回
        return Response::jsonSuccess('短信发送成功！');
    }
}
