<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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
            'platform' => 'required',
        ], [], $attributes);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 501);
        }

        return $next($request);
    }
}