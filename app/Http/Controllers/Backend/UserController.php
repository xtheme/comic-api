<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.user.index')->with($data);
    }

    /*public function create()
    {
        $username = '茄子漫画' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);

        return view('backend.user.create', [
            'username' => $username,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validated();

        $isUsernameExist = User::where('username', $validated['username'])->count();
        if ($isUsernameExist) {
            return Response::jsonError('用户名已存在！');
        }

        $isMobileExist = User::where('mobile', $validated['mobile'])->count();
        if ($isMobileExist) {
            return Response::jsonError('相同手机号的用户已存在！');
        }

        User::create($validated);

        return Response::jsonSuccess('新增用户成功！');
    }*/


    public function edit($id)
    {
        $data = [
            'user' => User::findOrFail($id)
        ];

        return view('backend.user.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        // $validated = $request->validated();

        $model = User::findOrFail($id);

        $model->fill($request->input())->save();

        return Response::jsonSuccess('資料已更新！');
    }

    public function editVip($id)
    {
        $data = [
            'user' => User::findOrFail($id)
        ];

        return view('backend.user.edit_vip')->with($data);
    }

    public function updateVip(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->subscribed_at) {
            $user->subscribed_at = $user->subscribed_at->addDays($request->input('day'));
        } else {
            $user->subscribed_at = Carbon::now()->addDays($request->input('day'));
        }

        $user->save();

        return Response::jsonSuccess('資料已更新！');
    }

    public function destroy($id)
    {
        $model = User::findOrFail($id);

        $model->delete();

        return Response::jsonSuccess('資料已刪除！');
    }

    public function block($id)
    {
        $model = User::findOrFail($id);

        $model->status = $model->status != 1 ? 1 : 2;

        $model->save();

        return Response::jsonSuccess('資料已更新！');
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->post('ids'));

        switch ($action) {
            case 'enable':
                $text = '批量启用';
                $data = ['status' => 1];
                break;
            case 'disable':
                $text = '批量封禁';
                $data = ['status' => 0];
                break;
        }

        User::whereIn('id', $ids)->update($data);

        return Response::jsonSuccess($text . '成功！');
    }

    public function editable(Request $request, $field)
    {
        $data = [
            'pk' => $request->post('pk'),
            'value' => $request->post('value'),
        ];

        $validator = Validator::make($data, [
            'pk' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        $this->repository->editable($request->post('pk'), $field, $request->post('value'));

        return Response::jsonSuccess('数据已更新成功');
    }
}
