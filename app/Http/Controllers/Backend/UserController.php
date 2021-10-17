<?php

namespace App\Http\Controllers\Backend;

use App\Enums\UserOptions;
use App\Http\Controllers\Controller;
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

    // 贈送 VIP 或金幣
    public function gift($id)
    {
        $data = [
            'user' => User::findOrFail($id)
        ];

        return view('backend.user.edit_vip')->with($data);
    }

    public function updateGift(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->subscribed_until && $user->subscribed_until->greaterThan(Carbon::now())) {
            $user->subscribed_until = $user->subscribed_until->addDays($request->input('day'));
        } else {
            $user->subscribed_until = Carbon::now()->addDays($request->input('day'));
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

        if ($transfer_user->subscribed_until && $transfer_user->subscribed_until->greaterThan($current_user->subscribed_until)) {
            return Response::jsonError('目标的会员效期高于当前用户');
        }

        $transfer_user->subscribed_until = $current_user->subscribed_until;
        $transfer_user->save();

        $current_user->subscribed_until = null;
        $current_user->save();

        $text = sprintf('将 #%s 的 VIP 效期 %s 转移到 #%s', $current_user->id, $transfer_user->subscribed_until, $transfer_user->id);
        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($transfer_user)->withProperties($transfer_user->getChanges())->log($text);

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
