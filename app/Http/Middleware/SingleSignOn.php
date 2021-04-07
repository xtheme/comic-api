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
        $uuid = $request->header('uuid');
        $area = $request->input('area') ?? null;
        $mobile = $request->input('mobile') ?? null;

        if ($mobile) {
            $sso_key = sprintf('sso:%s-%s', $area, $mobile);
            $device_id = Cache::get($sso_key);

            if ($device_id && $device_id != $uuid) {
                return Response::jsonError('请您先退出旧设备再登录！', 996);
            }

            // 记录设备号
            Cache::forever($sso_key, $uuid);
        }

        return $next($request);
    }
}
