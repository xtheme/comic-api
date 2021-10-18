<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\UserPurchaseLog;
use App\Models\UserRechargeLog;
use Gateway;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UserService
{
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
    public function updateUserPlan(User $user, $plan)
    {
        $coin = $days = 0;
        $coin += $plan['coin'] ?? 0;
        $coin += $plan['gift_coin'] ?? 0;
        $days += $plan['days'] ?? 0;
        $days += $plan['gift_days'] ?? 0;

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
    public function logUserRecharge(array $data)
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
        $this->logUserRecharge($data);

        // 添加每日限額
        Gateway::incDailyLimit($order->payment_id, $order->amount);
    }

    /**
     * 後台手動上分
     */
    public function manualUpdateOrder(Order $order)
    {
        $user = User::findOrFail($order->user_id);

        // 更新订单数据
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
        $this->logUserRecharge($data);

        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($order)->withProperties($order->getChanges())->log('手动回调订单');
    }

    /**
     * 建立用戶消費(購買)紀錄
     */
    public function purchase($type, $item_id, $coin)
    {
        $hasBought = UserPurchaseLog::where(function ($query) use ($type, $item_id) {
            $query->where('user_id', request()->user()->id)
                  ->where('type', $type)
                  ->where('item_id', $item_id);
        })->first();

        // 建立用戶消費(購買)紀錄
        $data = [
            'user_id' => request()->user()->id,
            'type' => $type,
            'item_id' => $item_id,
            'coin' => $coin,
        ];

        UserPurchaseLog::firstOrCreate($data);
    }
}
