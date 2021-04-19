<?php

namespace App\Http\Controllers\Api;

use App\Services\SmsService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

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
     * @return \Illuminate\Http\JsonResponse
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
     *
     * @return \Illuminate\Http\JsonResponse
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

        return Response::jsonSuccess($response);
    }

    /**
     * 退出登录
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
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

        return Response::jsonSuccess($response);
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

        return Response::jsonSuccess($user);
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

        // 临时文件的绝对路径
        $realPath = $file->getRealPath();

        // 获取后缀名
        $ext = $file->getClientOriginalExtension();

        // 生成文件路径
        // $filename = '/uploads/avatar/' . uniqid() . '.' . $ext;
        $filename = '/avatar/' . Hashids::encode($user->id) . '.' . $ext;

        // 上传文件
        $success = Storage::put($filename, file_get_contents($realPath));

        if (!$success) {
            return Response::jsonError('很抱歉，上传头像失败！');
        }

        // $user->userface = $filename;
        $user->avatar = '/storage' . $filename;
        $user->save();

        // 刷新缓存
        $this->userService->updateUserCache($user);

        return Response::jsonSuccess($user);
    }
}