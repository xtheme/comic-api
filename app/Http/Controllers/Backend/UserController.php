<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 用户列表
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function list(Request $request)
    {
        $users = User::paginate();

        return view('backend.user.list', [
                'list'           => $users,
                'pageConfigs'    => ['hasSearchForm' => false],
            ]);
    }

    /**
     * 封封禁户
     *
     * @param $id
     *
     * @return Response
     */
    // public function block($id)
    // {
    //     if (empty($id)) {
    //         return Response::jsonError('缺少核心参数！');
    //     }
    //     $user = User::findOrFail($id);
    //     if ($user->status == 1) {
    //         $user->status = 2;
    //     } else {
    //         $user->status = 1;
    //     }
    //     $user->save();
    //
    //     return Response::jsonSuccess('操作成功！');
    // }

    /**
     * 删除用户 - 软删除
     *
     * @param $id
     *
     * @return Response
     */
    // public function destroy($id)
    // {
    //     if (empty($id)) {
    //         return Response::jsonError('缺少核心参数！');
    //     }
    //     // 变更为软删除
    //     // User::where('id', $id)->update(['status' => 3]);
    //     $user = User::findOrFail($id);
    //     $user->delete();
    //
    //     return Response::jsonSuccess('操作成功！');
    // }

    /**
     * 恢復軟删除用户
     *
     * @param $id
     *
     * @return Response
     */
    // public function restore($id)
    // {
    //     if (empty($id)) {
    //         return Response::jsonError('缺少核心参数！');
    //     }
    //
    //     // 恢復軟删除
    //     User::withTrashed()->findOrFail($id)->restore();
    //
    //     return Response::jsonSuccess('操作成功！');
    // }

    /**
     * 更新用户资料
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        return view('backend.user.edit', [
                'user'        => User::find($id),
                // 'user_status' => Options::USER_STATUS,
            ]);
    }

    /**
     * 更新用户资料
     *
     * @param  UserRequest  $request
     * @param $id
     *
     * @return Response
     */
    public function update(UserRequest $request, $id)
    {
        $post = $request->post();

        $user = User::findOrFail($id);

        $user->fill($post)->save();

        return Response::jsonSuccess('更新资料成功！');
    }

    /**
     * 新增用户
     *
     * @return View
     */
    // public function create()
    // {
    //     return view('pages.user.create');
    // }

    /**
     * 新增用户
     *
     * @param  UserRequest  $request
     *
     * @return Response
     */
    // public function store(UserRequest $request)
    // {
    //     $post = $request->post();
    //
    //     unset($post['password_verify']);
    //
    //     $username = generateRandomString(12);
    //
    //     if (User::isUsernameExist($username)) {
    //         return Response::jsonError('用户名已存在！');
    //     }
    //
    //     if (User::isMobileExist($post['mobile'])) {
    //         return Response::jsonError('相同手机号的用户已存在！');
    //     }
    //
    //     $user = new User();
    //
    //     $user->username = $username;
    //     $user->code = generateRandomString();
    //     $user->integral = 5;
    //
    //     $user->fill($post)->save();
    //
    //     return Response::jsonSuccess('新增用户成功！');
    // }

    /**
     * 批量审核
     *
     * @param $flag
     * @param $ids
     *
     * @return Response
     */
    // public function updateStatus($flag, $ids)
    // {
    //     if (empty($ids)) {
    //         return Response::jsonError('请选择要操作项！');
    //     }
    //
    //     if (empty($flag)) {
    //         return Response::jsonError('缺少核心参数！');
    //     }
    //
    //     $ids = explode(',', $ids);
    //
    //     $result = User::whereIn('id', $ids)->update(['status' => $flag]);
    //
    //     if ($result) {
    //         return Response::jsonSuccess('批量操作成功！');
    //     }
    //
    //     return Response::jsonError('操作失败！');
    // }

    /**
     * 设备列表
     *
     * @param $user_id
     *
     * @return View
     */
    // public function devices($user_id)
    // {
    //     // 由登入紀錄中獲取該用戶曾經使用過的設備
    //     $relation_devices = UserDevice::with('detail')->groupBy('device_id')->where('user_id', $user_id)->paginate(config('custom.perpage'));
    //
    //     return view('pages.user.devices', [
    //             'devices' => $relation_devices,
    //             'user'    => User::FindOrFail($user_id),
    //         ]);
    // }
}
