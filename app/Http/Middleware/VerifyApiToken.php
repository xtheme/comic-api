<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use App\Services\UserService;
use App\Traits\CacheTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Validator;

class VerifyApiToken
{
    use CacheTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /*$request_route = $request->route()->getName();

        // UserController@device 不帶 token 請求時, 將作為 token 發行用途, 可略過檢查
        if ($request_route == 'api.user.device' && !$request->header('token')) {
            return $next($request);
        }*/

        $data = [
            'token' => $request->bearerToken(),
        ];

        $validator = Validator::make($data, [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        try {

            $decoded = JwtService::tokenVerify($data['token']);

            // 检查 iss 与当前的 uuid 是否相符
            if ($decoded->sub !== $request->header('uuid')) {
                return Response::jsonError('设备异常！', 580);
            }
        } catch (\Exception $e) {
            return Response::jsonError(__('jwt.' . $e->getMessage()), 583);
        }

        return $next($request);
    }
}
