<?php

namespace App\Http\Controllers\Api;

use App\Services\SmsService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 初始化接口
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function device(Request $request)
    {
        $uuid = $request->header('uuid');
        $area = $request->user ? $request->user->area :  null;
        $mobile = $request->user ? $request->user->mobile : null;

        // 有绑定电话时, 使用电话账号登入
        if (!empty($area) && !empty($mobile)) {
            $user = $this->userService->getUserByMobile($area, $mobile); // return Model (Object)
            $cache_key = $this->getCacheKeyPrefix() . sprintf('user:mobile:%s-%s', $area, $mobile);
        } else {
            // 使用设备账号登入 (访客)
            $user = $this->userService->getUserByDevice(); // return Model (Object)
            $cache_key = $this->getCacheKeyPrefix() . sprintf('user:device:%s', $uuid);
        }

        if (!$user) {
            // 针对此新设备生成用户数据
            $user = $this->userService->registerDevice($request); // return Model (Object)
        }

        if (!$user->status) {
            return Response::jsonError('很抱歉，您的账号已被禁止！');
        }

        $response = $this->userService->addDeviceCache($cache_key, $user);

        return Response::jsonSuccess($response);
    }

    /**
     * 用户短信登录与注册 (手机绑定)
     *
     * @param  Request  $request
     */
    public function mobile(Request $request)
    {
        // $uuid = $request->header('uuid');
        $area = $request->input('area') ?? 86;
        $mobile = $request->input('mobile');
        $sms_code = $request->input('sms_code');

        // 验证规则
        $validator = Validator::make([
            'mobile' => $mobile,
            'sms_code' => $sms_code,
        ], [
            'mobile'   => 'required|numeric',
            'sms_code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        if (config('api.sms.check')) {
            if (!(new SmsService())->isVerifyCode($mobile, $area, $sms_code)) {
                return Response::jsonError('很抱歉，短信验证码不正确！');
            }
        }

        // 检查手机号是否已被绑定
        $mobile_user = $this->userService->getUserByMobile($area, $mobile);

        if (!$mobile_user) {
            // 新建立一個手機號註冊帳號
            $mobile_user = $this->userService->registerMobile($request);
        }

        if (!$mobile_user->status) {
            return Response::jsonError('很抱歉，您的账号已被禁止！', 500);
        }

        // 前任裝置帳號
        $device_user = $this->userService->getUserByDevice();

        // 關聯訂單到手機帳號
        $this->userService->relationOrder($mobile_user, $device_user);

        // 轉讓 VIP 訂閱到手機帳號
        $this->userService->transferSubscribed($mobile_user, $device_user);

        // 紀錄綁定歷史
        $data = [
            'mobile' => sprintf('%s-%s', $area, $mobile),
            'device_user_id' => $device_user->id,
            'action' => 1, // 綁定
        ];
        $this->userService->addBindLog($data);

        // 清除可能在其他裝置登入的緩存, 強迫重新生成 token
        $cache_key = $this->getCacheKeyPrefix() . sprintf('user:mobile:%s-%s', $area, $mobile);
        Cache::forget($cache_key);
        $response = $this->userService->addDeviceCache($cache_key , $mobile_user);

        // SSO 单点登入, 记录设备号
        // Cache::set($sso_key, $uuid);

        return Response::jsonSuccess($response);
    }
}
