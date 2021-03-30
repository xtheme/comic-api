<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $data = [
            'list' => User::paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.user.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        $username = '茄子漫画' . substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);

        return view('backend.user.create', [
            'username' => $username,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        $data = [
            'user' => User::findOrFail($id)
        ];

        return view('backend.user.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validated();

        $model = User::findOrFail($id);

        $model->fill($validated)->save();

        return Response::jsonSuccess('資料已更新！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = User::findOrFail($id);

        $model->delete();

        return Response::jsonSuccess('資料已刪除！');
    }

    /**
     * 封封禁户
     *
     * @param $id
     *
     * @return Response
     */
    public function block($id)
    {
        $model = User::findOrFail($id);

        $model->status = $model->status != 1 ? 1 : 2;

        $model->save();

        return Response::jsonSuccess('資料已更新！');
    }
}
