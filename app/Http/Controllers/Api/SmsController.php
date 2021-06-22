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

    public function verify(MobileRequest $request)
    {
        $area = $request->input('area') ?? null;
        $mobile = $request->input('mobile') ?? null;
        $phone = sprintf('%s-%s', $area, $mobile);

        if (!Sso::checkPhone($phone)) {
            return Response::jsonError('请您先退出旧设备再登录！', 581);
        }

        return $this->send($request);
    }

    public function send(MobileRequest $request)
    {
        $area = $request->post('area') ? trim($request->post('area')) : '86';
        $mobile = $request->post('mobile') ? trim($request->post('mobile')) : '';
        $ip = $request->ip();

        // 验证规则
        $validator = Validator::make([
            'area' => $area,
            'mobile' => $mobile,
        ], [
            'area' => 'required|numeric',
            'mobile'   => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        // 限制每天发送次数
        if (!$this->smsService->limitTodayFrequency($area, $mobile)) {
            return Response::jsonError('当日短信额度已超出上限，请改天再试！');
        }

        // 限制手机号发送频率
        if (!$this->smsService->limitMobileFrequency($area, $mobile)) {
            return Response::jsonError('发送频率过快，请稍后再试！');
        }

        // 限制IP发送频率
        if (!$this->smsService->limitIpFrequency($ip)) {
            return Response::jsonError('发送频率过快，请稍后再试！');
        }

        // 发送验证码
        $this->smsService->send($area, $mobile);

        // 成功返回
        return Response::jsonSuccess('短信发送成功！');
    }
}
