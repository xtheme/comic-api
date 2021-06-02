<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Validator;

class VerifyJwtToken
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

        // UserController@device 不帶 token 請求時, 將作為 token 發行用途, 可略過檢查
        if ($request_route == 'api.user.device' && !$request->header('token')) {
            return $next($request);
        }

        $data = [
            'token' => $request->header('token'),
            'uuid' => $request->header('uuid'),
        ];

        $validator = Validator::make($data, [
            'token' => 'required',
            'uuid' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        try {
            $jwtService = new JwtService;
            $decoded = $jwtService->tokenVerify($data['token']);

            // 检查 iss 与当前的 uuid 是否相符
            if ($decoded->iss !== $data['uuid']) {
                return Response::jsonError('设备异常！');
            }

            // 若 token 中能获取 uid, 在请求中注入 user 属性
            if (isset($decoded->uid)) {
                $request->user = User::findOrFail($decoded->uid);

                // 帳號是否封禁
                if (!$request->user->status) {
                    return Response::jsonError('很抱歉，您的账号已被禁止！', 500);
                }

                // 電話帳號
                if ($request->user->mobile) {
                    // 检查请求的 token 跟用户数据的 token 是否相符
                    if ($data['token'] != $request->user->token) {
                        // 設備重複登入時只能登出, 否則一率報錯
                        if ($request_route == 'api.user.logout') {
                            return $next($request);
                        }
                        
                        return Response::jsonError('您已经在其他设备上登录！',  582);
                    }
                }
            } else {
                return Response::jsonError('Token 格式错误！');
            }
        } catch (\Exception $e) {
            // todo jwt 过期自动签发?
            return Response::jsonError(__('jwt.' . $e->getMessage()));
        }

        return $next($request);
    }
}
