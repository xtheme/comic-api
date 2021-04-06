<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends BaseController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * 初始化接口
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function device(Request $request)
    {
        $uuid = $request->header('uuid');
        $area = $request->user ? $request->user->area :  null;
        $mobile = $request->user ? $request->user->mobile : null;

        // 有绑定电话时, 使用电话账号登入
        if (!empty($area) && !empty($mobile)) {
            $user = $this->userService->getUserByMobile($area, $mobile); // return Model (Object)
            $cache_key = $this->getCacheKeyPrefix() . sprintf('user:mobile:%s-%s', $area, $mobile);
        } else {
            // 使用设备账号登入 (访客)
            $user = $this->userService->getUserByDevice(); // return Model (Object)
            $cache_key = $this->getCacheKeyPrefix() . sprintf('user:device:%s', $uuid);
        }

        if (!$user) {
            // 针对此新设备生成用户数据
            $user = $this->userService->registerDevice(); // return Model (Object)
        }

        if (!$user->status) {
            Response::jsonError('很抱歉，您的账号已被禁止！');
        }

        $response = $this->userService->addDeviceCache($cache_key, $user);

        return Response::jsonSuccess($response);
    }
}
