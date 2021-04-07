<?php

namespace App\Services;

use App\Models\BindLog;
use App\Models\Order;
use App\Models\User;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserService
{
    /**
     * @return User
     */
    public function getUserByDevice()
    {
        $uuid = request()->header('uuid');

        return User::whereDeviceId($uuid)->first();
    }

    /**
     * 获取设备对应的用户数据
     *
     * @param $area
     * @param $mobile
     *
     * @return User
     */
    public function getUserByMobile($area, $mobile)
    {
        return User::whereArea($area)->whereMobile($mobile)->first();
    }

    /**
     * 數據組成 寫入緩存
     *
     * @param $cache_key
     * @param $user
     * @param  bool  $issue_token  重新签发 JWT
     *
     * @return mixed
     */
    public function addDeviceCache($cache_key, $user, bool $issue_token = true)
    {
        $cache_ttl = config('api.jwt.ttl');

        return Cache::remember($cache_key, $cache_ttl, function () use ($user, $issue_token) {
            if (request()->header('app-version')) {
                // 签发 JWT
                if ($issue_token) {
                    $token = $this->genToken($user->id);
                    $user->token = $token;
                }

                // 更新资料
                // $user->last_login_time = time();
                $user->last_login_at = Carbon::now();
                $user->last_login_ip = request()->header('ip');
                $user->platform = request()->header('platform');
                $user->version = request()->header('app-version');
                $user->save();
            }

            return $user->toArray();
        });
    }

    /**
     * 生成 token
     *
     * @param $uid
     *
     * @return string
     */
    public function genToken($uid): string
    {
        $data = [
            'iss' => request()->header('uuid'), //该JWT的签发者
            'uid' => $uid,
            'ip' => request()->ip(),
            'iat' => time(), // 签发时间
            'exp' => time() + config('api.jwt.ttl'), // 过期时间
            'nbf' => time() + 1, // 该时间之前不接收处理该Token
            'sub' => request()->server('HTTP_HOST'), // 面向的用户
            'jti' => md5(uniqid('JWT', true) . time()) // 该Token唯一标识
        ];

        $JwtService = new JwtService();

        return $JwtService->getToken($data);
    }

    /**
     * 随机读取用户昵称
     *
     * @return string
     */
    public function getUserNiceName(): string
    {
        return config('api.account.prefix') . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);
    }

    /**
     * 注册设备
     *
     * @param  Request  $request
     *
     * @return User
     */
    public function registerDevice(Request $request)
    {
        $data = [
            'username' => $this->getUserNiceName(),
            'userface' => '',
            'signup_ip' => $request->header('ip'),
            'create_time' => time(),
            'status' => 1,
            'role' => 3,
            'sex' => 0,
            'device_id' => $request->header('uuid'),
            'platform' => $request->header('platform'),
            'version' => $request->header('app-version'),
            'subscribed_at' => null,
            'mobile_bind' => 0,
        ];

        return User::create($data);
    }

    /**
     * 注册手機號用戶
     *
     * @param  Request  $request
     *
     * @return User
     */
    public function registerMobile(Request $request)
    {
        $data = [
            'username' => $this->getUserNiceName(),
            'userface' => '',
            'signup_ip' => $request->header('ip'),
            'create_time' => time(),
            'status' => 1,
            'role' => 3,
            'sex' => 0,
            'mobile_bind' => 1,
            'area' => $request->input('area'),
            'mobile' => $request->input('mobile'),
            'platform' => $request->header('platform'),
            'version' => $request->header('app-version'),
            'subscribed_at' => null,
        ];

        return User::create($data);
    }

    /**
     * 關聯訂單到手機帳號
     *
     * @param  User  $mobile_user
     * @param  User  $device_user
     */
    public function relationOrder(User $mobile_user, User $device_user)
    {
        $where = [
            'user_id' => $device_user->id,
            'mobile' => '',
        ];

        $update = [
            'mobile' => sprintf('%s-%s', $mobile_user->area, $mobile_user->mobile),
        ];

        Order::where($where)->update($update);
    }

    /**
     * 轉讓 VIP 訂閱
     *
     * @param  User  $mobile_user
     * @param  User  $device_user
     */
    public function transferSubscribed(User $mobile_user, User $device_user)
    {
        // 比對 VIP 時效
        $prev_subscribed_at = $device_user->subscribed_at ? strtotime($device_user->subscribed_at) : null;

        if ($prev_subscribed_at) {
            $current_subscribed_at = $mobile_user->subscribed_at ? strtotime($mobile_user->subscribed_at) : time();
            $keep_subscribed_at = ($prev_subscribed_at > $current_subscribed_at) ? $prev_subscribed_at : $current_subscribed_at;

            // 更新電話帳號 VIP 時效
            $mobile_user->subscribed_at = date('Y-m-d H:i:s', $keep_subscribed_at);
            $mobile_user->save();

            // 清空裝置帳號 VIP 時效
            $device_user->subscribed_at = null;
            $device_user->save();
        }
    }

    /**
     * 新增綁定紀錄
     * action 操作行為，1:綁定，2:解綁，3:後台解綁
     *
     * @param  array  $data
     */
    public function addBindLog(array $data)
    {
        return BindLog::create($data);
    }
}
