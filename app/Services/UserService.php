<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Gateway;

class UserService
{
    /**
     * 訂單回調成功, 上分
     */
    public function updateOrder(Order $order, $transaction_id)
    {
        $user = User::findOrFail($order->user_id);

        // 更新订单数据
        $update = [
            'app_id' => $user->app_id, // 財務報表用
            'channel_id' => $user->channel_id, // 財務報表用
            'first' => $user->isRenew() ? 0 : 1,
            'status' => 1,
            'transaction_id' => $transaction_id,
            'transaction_at' => date('Y-m-d H:i:s'),
        ];
        $order->update($update);

        // 更新用戶錢包或VIP時效 && 建立用戶充值紀錄
        $user->saveRecharge($order);

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
            'app_id' => $user->app_id, // 財務報表用
            'channel_id' => $user->channel_id, // 財務報表用
            'first' => $user->isRenew() ? 0 : 1,
            'status' => 1,
        ];
        $order->update($update);

        // 更新用戶錢包或VIP時效 && 建立用戶充值紀錄
        $user->saveRecharge($order);

        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($order)->withProperties($order->getChanges())->log('手动回调订单');
    }
}
