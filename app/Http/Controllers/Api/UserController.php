<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\MobileRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use App\Services\SmsService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Sso;
use Upload;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        if ($this->userService->isNameExists($data['name'])) {
            return Response::jsonError('很抱歉，账号已经被注册！');
        }

        $user = User::create($data);

        // 簽發 token
        $user['token'] = $user->createToken('api')->plainTextToken;

        return Response::jsonSuccess(__('api.success'), $user);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $loginField = filter_var($data['name'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $user = User::where($loginField, $data['name'])->first();

        if (!Hash::check($data['password'], $user->password)) {
            return Response::jsonError('密码错误！');
        }

        // 簽發 token
        $user['token'] = $user->createToken('api')->plainTextToken;

        return Response::jsonSuccess(__('api.success'), $user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return Response::jsonSuccess(__('api.success'), [], 204);
    }





    /**
     * 个人编辑
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function modify(Request $request)
    {
        $user = $this->userService->update($request);

        if (is_object($user)) {
            return Response::jsonSuccess(__('api.success'), $user);
        }

        return Response::jsonError($user, 500);
    }

    /**
     * 上传头像 (dt/uploadUserPhoto)
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatar(Request $request)
    {
        $file = $request->file('avatar');

        if (!$file->isValid()) {
            return Response::jsonError('请选择要上传的头像！');
        }

        $user = $request->user;

        $response = Upload::to('user' , $user->id)->store($request->file('avatar'));

        if ($response['success'] != 1){
            return Response::jsonError('很抱歉，上传头像失败！');
        }

        $user->avatar = $response['path'];

        $user->save();

        return Response::jsonSuccess(__('api.success'), $user);
    }

    /**
     * 签到
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sign(Request $request)
    {

        $sign_days = $this->userService->days($request->user);

        $exists = $sign_days->pluck('date')->contains(date('Y-m-d'));

        if ($exists) {
            return Response::jsonError('今日已签到');
        }

        $exists = $sign_days->pluck('date')->contains(date('Y-m-d', strtotime('-1 day')));

        $days = 0;
        if ($exists) {
            $days = $request->user->sign_days;
        }

        // 寫入簽到
        $data = $this->userService->sign_in($days);

        $data = [
            'score' => $data['score'],
            'days' => $data['days']
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }

}
