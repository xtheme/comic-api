<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class SingleSignOn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request_route = $request->route()->getName();

        $white_routes = [
            'api.user.mobile',
            'api.sms.send',
        ];

        if (in_array($request_route, $white_routes)) {
            return $next($request);
        }

        // 第一次請求 user/device 不帶 token 狀況下 $request->user 不存在
        if (!$request->user) {
            return $next($request);
        }

        $uuid = $request->header('uuid');
        $area = $request->user->area;
        $mobile = $request->user->mobile;

        if ($mobile) {
            $sso_key = sprintf('sso:%s-%s', $area, $mobile);
            $device_id = Cache::get($sso_key);

            if ($device_id) {
                if ($device_id != $uuid) {
                    return Response::jsonError('请您先退出旧设备再登录！', 996);
                }
            } else {
                Cache::forever($sso_key, $uuid);
            }
        }

        return $next($request);
    }
}
