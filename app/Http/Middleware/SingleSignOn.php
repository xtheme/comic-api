<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Sso;

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
            'api.sms.verify',
            'api.sms.send',
            'api.user.logout',
        ];

        if (in_array($request_route, $white_routes)) {
            return $next($request);
        }

        // 第一次請求 user/device 不帶 token 狀況下 $request->user 不存在
        if (!$request->user()) {
            return $next($request);
        }

        if (!Sso::checkUser($request->user())) {
            return Response::jsonError('请您先退出旧设备再登录！', 581);
        }

        return $next($request);
    }
}
