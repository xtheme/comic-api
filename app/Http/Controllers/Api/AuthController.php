<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\ProfileResource;
use App\Jobs\RegisterJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthController extends BaseController
{
    /**
     * 驗證碼
     */
    public function captcha(Request $request)
    {
        $data = [
            'url' => app('captcha')->create('math', true),
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }

    /**
     * 登入帳號
     */
    public function login(LoginRequest $request)
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

        // 清除所有 token
        $user->tokens()->delete();

        // 簽發 personal token
        $token = $user->createToken($user->name)->plainTextToken;

        $response = (new ProfileResource($user))->withToken($token);

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 註冊帳號
     */
    public function register(RegisterRequest $request)
    {
        $input = $request->validated();

        // 檢查用戶名(信箱)是否已被使用
        if (true === User::where('name', strtolower($input['name']))->exists()) {
            return Response::jsonError(__('api.register.name.exists'));
        }

        $data = [
            'app_id' => $request->input('app') ?? 0,
            'channel_id' => $request->header('ch') ?? 1,
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
        // 清除所有 token
        $request->user()->tokens()->delete();

        return Response::jsonSuccess(__('api.logout.success'));
    }

    /**
     * 獲取用戶信息
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        $response = (new ProfileResource($user));

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 修改用戶信息
     */
    public function modify(Request $request)
    {
        $user = $request->user();

        if ($request->input('password') && $request->input('new_password')) {
            if (!Hash::check($request->post('password'), $user->getAuthPassword())) {
                return Response::jsonError('原密码验证错误！');
            }
        }

        if (!empty($request->input('new_password')) || !empty($request->input('new_password_confirm'))) {
            if ($request->input('new_password') != $request->input('new_password_confirm')) {
                return Response::jsonError('两次输入的新密码不同！');
            }

            $user->password = $request->input('new_password');
        }

        $user->save();

        $response = (new ProfileResource($user));

        return Response::jsonSuccess(__('api.success'), $response);
    }

    /**
     * 重新簽發 token
     */
    public function refresh(Request $request)
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
