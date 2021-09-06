<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class VerifyApiSign
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
        $sign = $request->header('sign');

        $data = [
            'timestamp' => $request->header('timestamp'),
        ];

        $validator = Validator::make($data, [
            'timestamp' => [
                'required',
            ],
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        // 按键名升序排列
        ksort($data);

        // 值用.拼接成字符串
        $str = implode('.', array_values($data));
        $str = md5(md5($str) . config('api.jwt.key'));

        if ($sign != $str) {
            return Response::jsonError('签名不正确, 授权失败!', 500);
        }

        return $next($request);
    }
}
