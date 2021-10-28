<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Jobs\RegisterJob;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends BaseController
{
    /**
     * 登入帳號
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $input = $request->validated();

        $user = User::where('name', $input['name'])->first();

        if (!$user) {
            return Response::jsonError(__('api.login.password.wrong'));
        }

        if (!Hash::check($input['password'], $user->password)) {
            return Response::jsonError(__('api.login.password.wrong'));
        }

        if (!$user->is_active) {
            return Response::jsonError(__('api.login.status.ban'));
        }

        // 更新登入時間
        $user->logged_at = Carbon::now();
        $user->save();

        // 簽發 personal token
        $user->tokens()->delete();
        $token = $user->createToken($user->name)->plainTextToken;

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'area' => $user->area,
            'mobile' => $user->mobile,
            'wallet' => $user->wallet,
            'subscribed_until' => optional($user->subscribed_until)->format('Y-m-d H:i:s'),
            'logged_at' => optional($user->logged_at)->format('Y-m-d H:i:s'),
            'token' => $token,
        ];

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 註冊帳號
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->validated();

        // 檢查用戶名(信箱)是否已被使用
        if (true === User::where('name', strtolower($input['name']))->exists()) {
            return Response::jsonError(__('api.register.name.exists'));
        }

        $data = [
            'app_id' => $request->input('app') ?? 0,
            'channel_id' => $request->input('ch') ?? 1,
            'name' => $request->input('name'),
            'password' => $request->input('password'),
            'wallet' => getConfig('app', 'register_coin'),
        ];

        $user = User::create($data);

        // 簽發 personal token
        $token = $user->createToken($user->name)->plainTextToken;

        RegisterJob::dispatch($user, $request->header('platform'));

        return Response::jsonSuccess(__('api.success'), ['token' => $token]);
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
        $user = $request->user();

        $response = [
            'id' => $user->id,
            'name' => $user->name,
            'area' => $user->area,
            'mobile' => $user->mobile,
            'wallet' => $user->wallet,
            'subscribed_until' => optional($user->subscribed_until)->format('Y-m-d H:i:s'),
            'logged_at' => optional($user->logged_at)->format('Y-m-d H:i:s'),
        ];

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 重新簽發 token
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->status) {
            return Response::jsonError(__('api.login.status.ban'));
        }

        // 清除所有 token
        $user->tokens()->delete();

        // 簽發 personal token
        $data = [
            'token' => $user->createToken($user->name)->plainTextToken,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
