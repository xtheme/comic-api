<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
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

        if ($user->subscribed_at && $user->subscribed_at->greaterThan(Carbon::now())) {
            $user->subscribed_at = $user->subscribed_at->addDays($request->input('day'));
        } else {
            $user->subscribed_at = Carbon::now()->addDays($request->input('day'));
        }

        $user->save();

        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($user)->withProperties($user->getChanges())->log('开通 VIP');

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function transferVip($id)
    {
        $data = [
            'user' => User::findOrFail($id)
        ];

        return view('backend.user.transfer_vip')->with($data);
    }

    public function transferUpdate(Request $request, $id)
    {
        $current_user = User::findOrFail($id);
        $transfer_user = User::findOrFail($request->post('user_id'));

        if ($transfer_user->subscribed_at && $transfer_user->subscribed_at->greaterThan($current_user->subscribed_at)) {
            return Response::jsonError('目标的会员效期高于当前用户');
        }

        $transfer_user->subscribed_at = $current_user->subscribed_at;
        $transfer_user->save();

        $current_user->subscribed_at = null;
        $current_user->save();

        $text = sprintf('将 #%s 的 VIP 效期 %s 转移到 #%s', $current_user->id, $transfer_user->subscribed_at, $transfer_user->id);
        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($transfer_user)->withProperties($transfer_user->getChanges())->log($text);

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function unbindSso($id)
    {
        $user = User::findOrFail($id);

        if (!$user->mobile) {
            return Response::jsonError('非手机帐号不需解绑');
        }

        $sso_key = sprintf('sso:%s-%s', $user->area, $user->mobile);

        if (!Cache::has($sso_key)) {
            return Response::jsonError('绑定纪录并不存在！');
        }

        Cache::forget($sso_key);

        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($user)->log('解绑手机登录限制: ' . $user->phone);

        return Response::jsonSuccess('已解绑该手机登录限制！');
    }

    public function destroy($id)
    {
        $model = User::findOrFail($id);

        $model->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function block($id)
    {
        $model = User::findOrFail($id);

        $model->status = $model->status != 1 ? 1 : 2;

        $model->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

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

        return Response::jsonSuccess(__('response.update.success'));
    }
}
