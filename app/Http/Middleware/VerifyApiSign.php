<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VerifyApiSign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $sign = $request->header('sign');

        $data = [
            'uuid'      => $request->header('uuid'),
            'timestamp' => $request->header('timestamp'),
        ];

        // 按键名升序排列
        ksort($data);

        // 值用.拼接成字符串
        $str = implode('.', array_values($data));
        $str = hash('sha256', $str . config('api.secret'));

        if ($sign != $str) {
            return Response::jsonError('签名不正确, 授权失败!', 500);
        }

        return $next($request);
    }
}
