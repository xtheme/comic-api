<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\UserModifyRequest;
use App\Http\Resources\ProfileResource;
use App\Jobs\RegisterJob;
use App\Models\User;
use Illuminate\Http\Request;
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
            'url' => app('captcha')->create('default', true),
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
        $user->logged_at = now();
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
            'wallet' => getConfig('app', 'register_point', 0),
            'logged_at' => now(),
        ];

        $user = User::create($data);

        // 簽發 personal token
        $token = $user->createToken($user->name)->plainTextToken;

        RegisterJob::dispatch($user, $request->header('platform'));

        $response = (new ProfileResource($user))->withToken($token);

        return Response::jsonSuccess(__('api.success'), $response);
    }

    function randomString($length_1 = 2, $length_2 = 4)
    {
        $str = substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length_1 / strlen($x)))), 1, $length_1);
        $str .= substr(str_shuffle(str_repeat($x = '0123456789', ceil($length_2 / strlen($x)))), 1, $length_2);

        return $str;
    }

    /**
     * 一鍵註冊
     * 檢查設備指紋是否匹配用戶帳號, 如是自動登入, 如否快速創建帳號
     */
    public function fastRegister(Request $request)
    {
        $fingerprint = $request->header('uuid');

        // 檢查設備指紋是否匹配用戶帳號
        $user = User::where('fingerprint', $fingerprint)->first();

        if ($user) {
            // 簽發 personal token
            $token = $user->createToken($user->name)->plainTextToken;

            $response = (new ProfileResource($user))->withToken($token);

            return Response::jsonSuccess(__('api.success'), $response);
        }

        $name = $this->randomString();
        $password = $this->randomString(0, 6);

        // 檢查用戶名(信箱)是否已被使用
        if (true === User::where('name', strtolower($name))->exists()) {
            return $this->fastRegister($request);
        }

        $data = [
            'app_id' => $request->input('app') ?? 0,
            'channel_id' => $request->header('ch') ?? 1,
            'name' => $name,
            'password' => $password,
            'fingerprint' => $request->header('uuid'),
            'wallet' => getConfig('app', 'register_point', 0),
            'logged_at' => now(),
        ];

        $user = User::create($data);

        // 簽發 personal token
        $token = $user->createToken($user->name)->plainTextToken;

        RegisterJob::dispatch($user, $request->header('platform'));

        $response = (new ProfileResource($user))->withToken($token)->withPassword($password);

        return Response::jsonSuccess(__('api.success'), $response);
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
    public function modify(UserModifyRequest $request)
    {
        $input = $request->validated();

        $user = $request->user();

        if (isset($input['name'])) {
            // 檢查用戶名(信箱)是否已被使用
            if (true === User::where('name', strtolower($input['name']))->where('id', '!=', $user->id)->exists()) {
                return Response::jsonError(__('api.register.name.exists'));
            }

            $user->name = $input['name'];
        }

        if (isset($input['password'])) {
            $user->password = $input['password'];
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
