<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OrderOptions;
use App\Enums\UserOptions;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Sso;
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

    public function edit($id)
    {
        $data = [
            'user' => User::withCount(['orders', 'success_orders', 'purchase_books'])->findOrFail($id),
            'active_options' => UserOptions::ACTIVE_OPTIONS,
            'ban_options' => UserOptions::BAN_OPTIONS,
        ];

        return view('backend.user.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        // $validated = $request->validated();

        $model = User::findOrFail($id);

        $model->fill($request->input())->save();

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function order($id)
    {
        $data = [
            'list' => User::findOrFail($id)->orders()->paginate(),
            'type_options' => OrderOptions::TYPE_OPTIONS,
            'platform_options' => OrderOptions::PLATFORM_OPTIONS,
            'status_options' => OrderOptions::STATUS_OPTIONS,
        ];

        return view('backend.user.order')->with($data);
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

        $plan = [
            'gift_coin' => (int) $request->input('gift_coin') ?? 0,
            'gift_days' => (int) $request->input('gift_days') ?? 0,
        ];

        if (!$plan['gift_coin'] && !$plan['gift_days']) {
            return Response::jsonError('请输入有效的数字');
        }

        // 更新用戶錢包或VIP時效
        app(UserService::class)->updateUserPlan($user, $plan);

        // 建立用戶充值紀錄
        $data = [
            'channel_id' => $user->channel_id,
            'user_id' => $user->id,
            'type' => 'gift',
            'admin_id' => Auth::user()->id,
            'gift_coin' => $plan['gift_coin'],
            'gift_days' => $plan['gift_days'],
        ];
        app(UserService::class)->addUseRechargeLog($data);

        activity()->useLog('后台')->causedBy(Auth::user())->performedOn($user)->withProperties($plan)->log(sprintf('赠送用户 %s 金币, VIP %s 天', $plan['gift_coin'], $plan['gift_days']));

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
            return Response::jsonError($validator->errors()->first(), 500);
        }

        $this->repository->editable($request->post('pk'), $field, $request->post('value'));

        return Response::jsonSuccess(__('response.update.success'));
    }
}
