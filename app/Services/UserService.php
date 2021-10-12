<?php

namespace App\Services;

use App\Models\User;
use App\Traits\CacheTrait;
use Cache;
use Illuminate\Http\Request;
// use Sso;

class UserService
{
    use CacheTrait;

    /**
     * 昵称是否被使用
     *
     * @param $username
     *
     * @return bool
     */
    public function isNameUsed($username)
    {
        return User::where('name', $username)->exists();
    }

    /**
     * 更新用户缓存
     *
     * @param  User  $user
     */
    public function updateUserCache(User $user)
    {
        $user->refresh();

        if (!empty($user->area) && !empty($user->mobile)) {
            $cache_key = $this->getCacheKeyPrefix($user->version) . sprintf('user:mobile:%s-%s', $user->area, $user->mobile);
        } else {
            $cache_key = $this->getCacheKeyPrefix($user->version) . sprintf('user:device:%s', $user->device_id);
        }

        Cache::forget($cache_key);

        $issue_token = false;

        $this->addDeviceCache($cache_key, $user, $issue_token);
    }

    /**
     * 清除用戶緩存
     *
     * @param  Request  $request
     *
     * @return void
     */
    public function unsetUserCache(Request $request)
    {
        $uuid = $request->user->device_id;
        $area = $request->user->area;
        $mobile = $request->user->mobile;

        // 登出清除緩存
        $uuid_key = $this->getCacheKeyPrefix() . sprintf('user:device:%s', $uuid);
        Cache::forget($uuid_key);

        if ($mobile) {
            $mobile_key = $this->getCacheKeyPrefix() . sprintf('user:mobile:%s-%s', $area, $mobile);
            Cache::forget($mobile_key);

            // SSO 单点登入
            // Sso::destroy($request->user->phone);
        }
    }
}
