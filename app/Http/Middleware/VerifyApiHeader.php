<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Validator;

class VerifyApiHeader
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
        $data = [
            'uuid' => $request->header('uuid'),
            'timestamp' => $request->header('timestamp'),
            'platform' => $request->header('platform'),
        ];

        $attributes = [
            'uuid' => '设备指纹',
            'timestamp' => '時間戳',
            'platform' => '平台代码',
        ];

        $validator = Validator::make($data, [
            'uuid' => 'required',
            'timestamp' => 'required',
            'platform' => [
                'required',
                Rule::in(['wap', 'app']),
            ],
        ], [], $attributes);

        if ($validator->fails()) {
            Log::emergency($validator->errors()->first());
            return Response::jsonError('缺少必要的请求参数!', 500);
        }

        // 验证时间戳, 接口有效期5分钟
        $time_lag = time() - (int) $request->header('timestamp');
        if ($time_lag > 300) {
            return Response::jsonError('请求已经过期！', 500);
        }

        return $next($request);
    }
}
