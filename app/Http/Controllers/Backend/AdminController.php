<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
            'list' => Admin::paginate(),
            'roles' => Role::all()->pluck('name')
        ];

        return view('backend.admin.index')->with($data);
    }

    public function create()
    {
        $data = [
            'roles' => Role::all()->pluck('name')
        ];

        return view('backend.admin.create')->with($data);
    }

    public function store(Request $request)
    {
        Admin::fill($request->post())->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'admin' => Admin::findOrFail($id),
            'roles' => Role::all()->pluck('name')
        ];

        return view('backend.admin.edit')->with($data);
    }

    public function update(AdminUpdateRequest $request, $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->username = $request->post('username');
        $admin->nickname = $request->post('nickname');
        $admin->status = $request->post('status');

        if ($request->post('password') && $request->post('new_password')) {
            if (!Hash::check($request->post('password'), $admin->getAuthPassword())) {
                return Response::jsonError('原密码验证错误');
            }
            // todo Admin 重構
            $admin->password = Hash::make($request->post('new_password'));
            // $admin->password = $request->post('new_password');
        }

        $admin->save();

        $admin->syncRoles($request->post('role'));

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        $admin->roles()->detach();
        $admin->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'enable':
                $text = '启用帐号';
                $data = ['status' => 1];
                Admin::whereIn('id', $ids)->update($data);
                break;
            case 'disable':
                $text = '封禁帐号';
                $data = ['status' => 0];
                Admin::whereIn('id', $ids)->update($data);
                break;
            case 'assign':
                $text = '指派角色';
                $role = $request->input('role');
                $admins = Admin::whereIn('id', $ids)->get();
                $admins->each(function ($admin) use ($role) {
                    $admin->syncRoles($role);
                });
                break;
            default:
                return Response::jsonError('未知的操作');
        }

        return Response::jsonSuccess($text . '成功！');
    }
}
