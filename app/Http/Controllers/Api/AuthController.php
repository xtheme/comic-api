<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Validator;

class AuthController extends BaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', [
    //         'except' => [
    //             'login',
    //             'register',
    //         ],
    //     ]);
    // }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $loginField = filter_var($data['name'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $user = User::where($loginField, $data['name'])->first();

        if (!Hash::check($data['password'], $user->password)) {
            return Response::jsonError('密码错误！');
        }

        // 簽發 personal token
        $token = $user->createToken('personal_token');
        $user->token = $token->plainTextToken;

        return Response::jsonSuccess(__('api.success'), $user);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $loginField = filter_var($data['name'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // 檢查用戶名/信箱重複
        if (true === User::where($loginField, $data['name'])->exists()) {
            return Response::jsonError(__('api.register.name.exists'));
        }

        $user = User::create($data);

        return Response::jsonSuccess(__('api.success'), $user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return Response::jsonSuccess(__('api.logout.success'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return Response::jsonSuccess(__('api.success'), $request->user());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => auth()->user(),
        ]);
    }

}