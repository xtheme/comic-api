<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\UserRechargeLog;
use App\Traits\CacheTrait;
use Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserService
{
    use CacheTrait;

    /**
     * 昵称是否被使用
     */
    public function isNameUsed(string $username): bool
    {
        return User::where('name', $username)->exists();
    }

    /**
     * 查詢用戶使否為首儲
     */
    public function isFirstOrder($user_id): bool
    {
        return !Order::where('user_id', $user_id)->where('status', 1)->exists();
    }

    /**
     * 更新用戶錢包或VIP時效
     */
    private function updateUserPlan(User $user, $plan)
    {
        $coin = $days = 0;
        $coin += $plan['coin'];
        $coin += $plan['gift_coin'];
        $days += $plan['days'];
        $days += $plan['gift_days'];

        $user->wallet = $user->wallet + $coin;

        if ($user->subscribed_until && $user->subscribed_until->greaterThan(Carbon::now())) {
            $user->subscribed_until = $user->subscribed_until->addDays($days);
        } else {
            $user->subscribed_until = Carbon::now()->addDays($days);
        }

        $user->save();
    }

    /**
     * 建立用戶充值紀錄
     */
    private function addUseRechargeLog(array $data)
    {
        UserRechargeLog::create($data);
    }

    /**
     * 訂單回調成功, 上分
     */
    public function updateOrder(Order $order, $transaction_id)
    {
        $user = User::findOrFail($order->user_id);

        // todo 更新订单数据
        $update = [
            'channel_id' => $user->channel_id, // 財務報表用
            'first' => $this->isFirstOrder($order->user_id) ? 1 : 0,
            'status' => 1,
            'transaction_id' => $transaction_id,
            'transaction_at' => date('Y-m-d H:i:s'),
        ];
        $order->update($update);

        // 更新用戶錢包或VIP時效
        $this->updateUserPlan($user, $order->plan_options);

        // 建立用戶充值紀錄
        $data = [
            'channel_id' => $user->channel_id,
            'user_id' => $order->user_id,
            'type' => $order->type,
            'order_id' => $order->order_id,
            'order_no' => $order->order_no,
            'coin' => $order->plan_options['coin'],
            'gift_coin' => $order->plan_options['gift_coin'],
            'days' => $order->plan_options['days'],
            'gift_days' => $order->plan_options['gift_days'],
        ];
        $this->addUseRechargeLog($data);

        // 添加每日限額
        Gateway::incDailyLimit($order->payment_id, $order->amount);
    }

    /**
     * 後台手動上分
     */
    public function manualUpdateOrder(Order $order)
    {
        $user = User::findOrFail($order->user_id);

        // todo 更新订单数据
        $update = [
            'channel_id' => $user->channel_id, // 財務報表用
            'first' => $this->isFirstOrder($order->user_id) ? 1 : 0,
            'status' => 1,
        ];
        $order->update($update);

        // 更新用戶錢包或VIP時效
        $this->updateUserPlan($user, $order->plan_options);

        // 建立用戶充值紀錄
        $data = [
            'channel_id' => $user->channel_id,
            'user_id' => $order->user_id,
            'type' => $order->type,
            'admin_id' => Auth::id(),
            'order_id' => $order->order_id,
            'order_no' => $order->order_no,
            'coin' => $order->plan_options['coin'],
            'gift_coin' => $order->plan_options['gift_coin'],
            'days' => $order->plan_options['days'],
            'gift_days' => $order->plan_options['gift_days'],
        ];
        $this->addUseRechargeLog($data);

        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($order)->withProperties($order->getChanges())->log('手动回调订单');
    }

    /**
     * 更新用户缓存
     *
     * @param  User  $user
     */
    /*public function updateUserCache(User $user)
    {
        $user->refresh();

        if (!empty($user->area) && !empty($user->mobile)) {
            $cache_key = $this->getCacheKeyPrefix($user->version) . sprintf('user:mobile:%s-%s', $user->area, $user->mobile);
        } else {
            $cache_key = $this->getCacheKeyPrefix($user->version) . sprintf('user:device:%s', $user->device_id);
        }

        Cache::forget($cache_key);

        $issue_token = false;

        $this->addDeviceCache($cache_key, $user, $issue_token);
    }*/

    /**
     * 清除用戶緩存
     *
     * @param  Request  $request
     *
     * @return void
     */
    /*public function unsetUserCache(Request $request)
    {
        $uuid = $request->user->device_id;
        $area = $request->user->area;
        $mobile = $request->user->mobile;

        // 登出清除緩存
        $uuid_key = $this->getCacheKeyPrefix() . sprintf('user:device:%s', $uuid);
        Cache::forget($uuid_key);

        if ($mobile) {
            $mobile_key = $this->getCacheKeyPrefix() . sprintf('user:mobile:%s-%s', $area, $mobile);
            Cache::forget($mobile_key);

            // SSO 单点登入
            Sso::destroy($request->user->phone);
        }
    }*/
}
