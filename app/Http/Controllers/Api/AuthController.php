<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends BaseController
{
    /**
     * 登入帳號
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $loginField = filter_var($data['name'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $user = User::where($loginField, $data['name'])->first();

        if (!Hash::check($data['password'], $user->password)) {
            return Response::jsonError('密码错误！');
        }

        // 簽發 personal token
        $user->token = $user->createToken($user->name)->plainTextToken;

        return Response::jsonSuccess(__('api.success'), $user);
    }

    /**
     * 註冊帳號
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $loginField = filter_var($data['name'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // 檢查用戶名(信箱)是否已被使用
        if (true === User::where($loginField, strtolower($data['name']))->exists()) {
            return Response::jsonError(__('api.register.name.exists'));
        }

        $user = User::create($data);

        return Response::jsonSuccess(__('api.success'), $user);
    }

    /**
     * 登出帳號, 清除所有 token
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return Response::jsonSuccess(__('api.logout.success'));
    }

    /**
     * 用戶信息
     */
    public function profile(Request $request): JsonResponse
    {
        return Response::jsonSuccess(__('api.success'), $request->user());
    }

    /**
     * 重新簽發 token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // 清除所有 token
        $user->tokens()->delete();

        // 簽發 personal token
        $data = [
            'token' => $user->createToken($user->name)->plainTextToken,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
