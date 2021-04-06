<?php

namespace App\Services;

use App\Models\User;
use Cache;
use Carbon\Carbon;

class UserService
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getUserByDevice()
    {
        $uuid = request()->header('uuid');
        return User::where('device_id', $uuid)->first();
    }

    /**
     * 获取设备对应的用户数据
     *
     * @param $area
     * @param $mobile
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function getUserByMobile($area , $mobile)
    {
        return User::where('area', $area)->where('mobile', $mobile)->first();
    }

    /**
     * 數據組成 寫入緩存
     *
     * @param $cache_key
     * @param $user
     * @param  bool  $issue_token 重新签发 JWT
     */
    public function addDeviceCache($cache_key , $user, $issue_token = true)
    {
        $cache_ttl = config('api.jwt.ttl');

        // return Cache::remember($cache_key, $cache_ttl, function () use ($user, $issue_token) {

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
        // });
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
            'ip'  => request()->ip(),
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
     */
    public function registerDevice()
    {
        $data = [
            'username'      => $this->getUserNiceName(),
            'userface'      => '',
            'signup_ip'     => request()->header('ip'),
            'create_time'   => time(),
            'status'        => 1,
            'role'          => 3,
            'sex'           => 0,
            'device_id'     => request()->header('uuid'),
            'platform'      => request()->header('platform'),
            'version'       => request()->header('app-version'),
            'subscribed_at' => null,
            'mobile_bind'   => 0,
        ];

        $user = new User($data);

        $user->save();

        return $this->getUserByDevice();
    }
}
