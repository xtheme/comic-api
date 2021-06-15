<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $list = Admin::paginate();

        return view('backend.admin.index', [
            'list' => $list
        ]);
    }

    public function create()
    {
        return view('backend.admin.create', [
            'roles' => Role::all()->pluck('name')
        ]);
    }

    public function store(Request $request)
    {
        $post = $request->post();

        Admin::fill($post)->save();

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);

        return view('backend.admin.edit', [
            'admin' => $admin,
            'roles' => Role::all()->pluck('name')
        ]);
    }

    // function salt($password)
    // {
    //     $password = md5($password);
    //     $salt = substr($password, 0, 5);
    //
    //     return $salt;
    // }
    //
    // function passCrypt($password)
    // {
    //     $salt = $this->salt($password);
    //     $password = crypt($password, $salt);
    //
    //     return $password;
    // }

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
        $ids = explode(',', $request->post('ids'));

        switch ($action) {
            case 'enable':
                $text = '启用帐号';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '封禁帐号';
                $data = ['status' => 0];
                break;
            default:
                return Response::jsonError('未知的操作');
        }

        Admin::whereIn('id', $ids)->update($data);

        return Response::jsonSuccess($text . '成功！');
    }
}
