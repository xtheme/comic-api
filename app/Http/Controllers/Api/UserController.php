<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\MobileRequest;
use App\Services\SmsService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Upload;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 初始化接口
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
            $user = $this->userService->getUserByDevice($request); // return Model (Object)
            $cache_key = $this->getCacheKeyPrefix() . sprintf('user:device:%s', $uuid);
            if (!$request->hasHeader('token') || $request->header('token') == '') {
                // 重新簽發 JWT
                Cache::forget($cache_key);
            }
        }

        if (!$user) {
            // 针对此新设备生成用户数据
            $user = $this->userService->registerDevice($request); // return Model (Object)
        }

        $response = $this->userService->addDeviceCache($cache_key, $user);

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 用户短信登录与注册 (手机绑定)
     */
    public function mobile(MobileRequest $request)
    {
        $area = $request->input('area') ?? 86;
        $mobile = $request->input('mobile');
        $sms_code = $request->input('sms_code');
        $force = $request->input('force') ?? false; // force reset sso

        if (config('api.sms.check')) {
            if (!(new SmsService())->isVerifyCode($mobile, $area, $sms_code)) {
                return Response::jsonError('很抱歉，短信验证码不正确！');
            }
        }

        $sso_key = sprintf('sso:%s-%s', $area, $mobile);

        if ($force) {
            Cache::forget($sso_key);
        } else {
            $uuid = $request->header('uuid');
            $device_id = Cache::get($sso_key);
            if ($device_id && $device_id != $uuid) {
                return Response::jsonError('请您先退出旧设备再登录！', 581);
            }
        }

        // 检查手机号是否已被绑定
        $mobile_user = $this->userService->getUserByMobile($area, $mobile);

        if (!$mobile_user) {
            // 新建立一個手機號註冊帳號
            $mobile_user = $this->userService->registerMobile($request);
        }

        // if (!$mobile_user->status) {
        //     return Response::jsonError('很抱歉，您的账号已被禁止！', 500);
        // }

        // 前任裝置帳號
        $device_user = $this->userService->getUserByDevice($request);

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
        $response = $this->userService->addDeviceCache($cache_key, $mobile_user);

        // todo SSO
        $sso_key = sprintf('sso:%s-%s', $area, $mobile);
        Cache::forever($sso_key, $request->header('uuid'));

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        $uuid = $request->header('uuid');

        // 清除用戶緩存 && SSO
        $this->userService->unsetUserCache($request);

        if ($this->userService->isUnusualUser($request)) {
            // 如果更新舊帳號成功則新建立設備帳號
            $user = $this->userService->registerDevice($request);
        } else {
            // 手機帳號退出後使用 device id 取得設備帳號
            $user = $this->userService->getUserByDevice($request);
        }

        // 紀錄綁定歷史
        $data = [
            'mobile' => sprintf('%s-%s', $request->user->area, $request->user->mobile),
            'device_user_id' => $user->id,
            'action' => 2, // 解綁
        ];
        $this->userService->addBindLog($data);

        $cache_key = $this->getCacheKeyPrefix() . sprintf('user:device:%s', $uuid);
        $response = $this->userService->addDeviceCache($cache_key, $user);

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 个人编辑
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function modify(Request $request)
    {
        $user = $this->userService->update($request);

        if (is_object($user)) {
            return Response::jsonSuccess(__('api.success'), $user);
        }

        return Response::jsonError($user, 500);
    }

    /**
     * 上传头像 (dt/uploadUserPhoto)
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatar(Request $request)
    {
        $file = $request->file('avatar');

        if (!$file->isValid()) {
            return Response::jsonError('请选择要上传的头像！');
        }

        $user = $request->user;

        $response = Upload::to('user' , $user->id)->store($request->file('avatar'));

        if ($response['success'] != 1){
            return Response::jsonError('很抱歉，上传头像失败！');
        }

        $user->avatar = $response['path'];

        $user->save();

        return Response::jsonSuccess(__('api.success'), $user);
    }

    /**
     * 签到
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sign(Request $request)
    {

        $sign_days = $this->userService->days($request->user);

        $exists = $sign_days->pluck('date')->contains(date('Y-m-d'));

        if ($exists) {
            return Response::jsonError('今日已签到');
        }

        $exists = $sign_days->pluck('date')->contains(date('Y-m-d', strtotime('-1 day')));

        $days = 0;
        if ($exists) {
            $days = $request->user->sign_days;
        }

        // 寫入簽到
        $data = $this->userService->sign_in($days);

        $data = [
            'score' => $data['score'],
            'days' => $data['days']
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }

}
