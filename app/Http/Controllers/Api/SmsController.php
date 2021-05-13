<?php

namespace App\Http\Controllers\Api;

use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SmsController extends BaseController
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function verify(Request $request)
    {
    }

    public function send(Request $request)
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
