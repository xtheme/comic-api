<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    private function getAllRoles()
    {
        return Role::all()->pluck('name');
    }

    public function index()
    {
        $data = [
            'list' => Admin::paginate(),
            'roles' => $this->getAllRoles(),
        ];

        return view('backend.admin.index')->with($data);
    }

    public function create()
    {
        $data = [
            'roles' => $this->getAllRoles(),
        ];

        return view('backend.admin.create')->with($data);
    }

    public function store(Request $request)
    {
        $post = $request->post();

        $admin = new Admin;

        $admin->fill($post)->save();

        activity()->useLog('后台')->causedBy(Auth::user())->performedOn($admin)->log('创建管理员帐号');

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'admin' => Admin::findOrFail($id),
            'roles' => $this->getAllRoles(),
        ];

        return view('backend.admin.edit')->with($data);
    }

    public function update(AdminUpdateRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->username = $request->post('username');
        $admin->nickname = $request->post('nickname');

        if ($request->post('password') && $request->post('new_password')) {
            $admin->password = $request->post('new_password');
        }

        $admin->save();
        $admin->syncRoles($request->post('role'));

        activity()->useLog('后台')->causedBy(Auth::user())->performedOn($admin)->log('编辑管理员帐号');

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        if ($id == 1) {
            return Response::jsonError('无法删除超级管理员');
        }

        $admin = Admin::findOrFail($id);
        $admin->roles()->detach();
        $admin->delete();

        activity()->useLog('后台')->causedBy(Auth::user())->performedOn($admin)->log('删除管理员帐号');

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        // 保護超級管理員
        $ids = array_filter($ids, function ($v, $k) {
            return $v != 1;
        }, ARRAY_FILTER_USE_BOTH);

        switch ($action) {
            case 'enable':
                $text = '启用管理员帐号';
                $data = ['status' => 1];
                Admin::whereIn('id', $ids)->update($data);
                break;
            case 'disable':
                $text = '封禁管理员帐号';
                $data = ['status' => 0];
                Admin::whereIn('id', $ids)->update($data);
                break;
            case 'assign':
                $text = '指派管理员角色';
                $role = $request->input('role');
                $admins = Admin::whereIn('id', $ids)->get();
                $admins->each(function ($admin) use ($role) {
                    $admin->syncRoles($role);
                });
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        activity()->useLog('后台')->causedBy(Auth::user())->withProperties($ids)->log($text);

        return Response::jsonSuccess($text . '成功！');
    }
}
