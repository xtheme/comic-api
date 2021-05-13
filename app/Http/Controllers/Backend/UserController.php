<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $data = [
            'list' => User::paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.user.index')->with($data);
    }

    public function create()
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
    }


    public function edit($id)
    {
        $data = [
            'user' => User::findOrFail($id)
        ];

        return view('backend.user.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $model = User::findOrFail($id);

        $model->fill($validated)->save();

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
}
