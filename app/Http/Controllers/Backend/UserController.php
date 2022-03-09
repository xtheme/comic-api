<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OrderOptions;
use App\Enums\UserOptions;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function edit($id)
    {
        $data = [
            'user' => User::withCount(['orders', 'success_orders', 'purchase_logs'])->findOrFail($id),
            'active_options' => UserOptions::ACTIVE_OPTIONS,
            'ban_options' => UserOptions::BAN_OPTIONS,
        ];

        return view('backend.user.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->area = $request->input('area');
        $user->mobile = $request->input('mobile');
        $user->is_active = $request->input('is_active');
        $user->is_ban = $request->input('is_ban');

        if (!empty($request->input('email'))) {
            if (!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
                return Response::jsonError('信箱格式不正确！');
            }
            $user->email = $request->input('email');
        }

        if (!empty($request->input('password')) || !empty($request->input('password_confirm'))) {
            if ($request->input('password') != $request->input('password_confirm')) {
                return Response::jsonError('两次密码不相符！');
            }

            $user->password = $request->input('password');
        }
        $user->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    // 訂單記錄
    public function order($id)
    {
        $data = [
            'list' => User::findOrFail($id)->success_orders()->paginate(),
            'type_options' => OrderOptions::TYPE_OPTIONS,
            'status_options' => OrderOptions::STATUS_OPTIONS,
        ];

        return view('backend.user.order')->with($data);
    }

    // 充值紀錄
    public function recharge($id)
    {
        $data = [
            'list' => User::findOrFail($id)->recharge_logs()->paginate(),
            'type_options' => OrderOptions::TYPE_OPTIONS,
        ];

        return view('backend.user.recharge')->with($data);
    }

    // 消費紀錄
    public function purchase($id)
    {
        $data = [
            'list' => User::findOrFail($id)->purchase_logs()->paginate(),
        ];

        return view('backend.user.purchase')->with($data);
    }

    // 贈送 VIP 或金幣
    public function gift($id)
    {
        $data = [
            'user' => User::findOrFail($id),
        ];

        return view('backend.user.gift')->with($data);
    }

    public function updateGift(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $gift = [
            'gift_coin' => (int) $request->input('gift_coin') ?? 0,
            'gift_days' => (int) $request->input('gift_days') ?? 0,
        ];

        if (!$gift['gift_coin'] && !$gift['gift_days']) {
            return Response::jsonError('请输入有效的数字！');
        }

        // 更新用戶錢包或VIP時效 && 建立用戶充值紀錄
        $user->saveGift($gift);

        activity()->useLog('后台')->causedBy(Auth::user())->performedOn($user)->withProperties($gift)->log(sprintf('赠送用户 %s 金币, VIP %s 天', $gift['gift_coin'], $gift['gift_days']));

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $model = User::findOrFail($id);

        $model->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    /**
     * 批次更新
     */
    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'active':
                $text = '批量启用';
                $data = ['is_active' => 1];
                break;
            case 'inactive':
                $text = '批量封禁';
                $data = ['is_active' => 0];
                break;
            case 'unblock':
                $text = '解除黑单';
                $data = ['is_ban' => 0];
                break;
            case 'block':
                $text = '标记黑单';
                $data = ['is_ban' => 1];
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
            return Response::jsonError($validator->errors()->first());
        }

        $this->repository->editable($request->post('pk'), $field, $request->post('value'));

        return Response::jsonSuccess(__('response.update.success'));
    }

    // 閱讀紀錄
    public function visit($id)
    {
        $user = User::findOrFail($id);

        $data = [
            'list' => $user->visit_logs()->with(['book'])->latest()->paginate(),
        ];

        return view('backend.user.visit')->with($data);
    }
}
