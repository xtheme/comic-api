<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PayRequest;
use App\Jobs\RechargeJob;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pricing;
use Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    // 支付方案
    public function pricing(Request $request)
    {
        // 用戶是否為首存, 檢查當前用戶是否有支付成功的訂單
        $target = [0, 1];

        if ($request->user()->isRenew()) {
            $target = [0, 2];
        }

        // 所有啟用的支付方案
        $pricing = Pricing::where('status', 1)->whereIn('target', $target)->orderBy('type')->orderByDesc('sort')->get();

        $pricing = $pricing->mapToGroups(function ($plan) {
            return [
                $plan->type => [
                    'plan_id' => $plan->id,
                    'type' => $plan->type,
                    'name' => $plan->name,
                    'description' => $plan->description,
                    'label' => $plan->label,
                    'price' => $plan->price,
                    'list_price' => $plan->list_price,
                    'coin' => $plan->coin,
                    'gift_coin' => $plan->gift_coin,
                    'days' => $plan->days,
                    'gift_days' => $plan->gift_days,
                    'target' => $plan->target,
                ],
            ];
        })->toArray();

        if (!$pricing) {
            return Response::jsonError('很抱歉，暂时没有任何支付方案！');
        }

        return Response::jsonSuccess(__('api.success'), $pricing);
    }

    // 支付渠道
    public function gateway(Request $request, $pricing_id)
    {
        $gateways = Pricing::findOrFail($pricing_id)->gateways()->get();

        $gateways = $gateways->reject(function ($gateway) {
            // 排除停用渠道
            return $gateway->status == 0;
        })->reject(function ($gateway) {
            // 排除每日限額已達上限
            $daily_total = Gateway::getDailyLimit($gateway->id);

            return $daily_total >= $gateway->daily_limit;
        })->map(function ($gateway) {
            return [
                'gateway_id' => $gateway->id,
                'button' => [
                    'text' => $gateway->button_text,
                    'icon' => !empty($gateway->button_icon) ? asset($gateway->button_icon) : '',
                    'target' => $gateway->button_target,
                ],
            ];
        })->toArray();

        if (!$gateways) {
            return Response::jsonError('很抱歉，此方案暂时没有配置支付渠道！');
        }

        return Response::jsonSuccess(__('api.success'), $gateways);
    }

    // 調用支付
    public function pay(PayRequest $request)
    {
        $input = $request->validated();
        $pricing_id = $input['pricing_id'];
        $payment_id = $input['payment_id'];

        // 限制用戶每小時訂單數
        $user = $request->user();

        // 黑名單用戶不允許建立訂單
        if ($user->is_ban) {
            return Response::jsonError('很抱歉，支付方案维护中！');
        }

        // 即時檢查用戶是否封禁
        if (!$user->is_active) {
            // 登出用戶
            $user->tokens()->delete();

            return Response::jsonError('请先登入您的帐号！');
        }

        $cache_key = 'hourly_orders:' . $user->id;
        if (Cache::has($cache_key)) {
            $hourly_orders = Cache::get($cache_key);
        } else {
            $hourly_orders = 0;
            Cache::set($cache_key, 0, 3600);
        }

        if ($hourly_orders >= getConfig('app', 'hourly_order_limit')) {
            return Response::jsonError('支付渠道冷却中，请稍后在试！');
        }

        try {
            $plan = Pricing::where('status', 1)->findOrFail($pricing_id);
        } catch (\Exception $e) {
            Log::warning(sprintf('用戶 %s 嘗試調用未開放的支付方案', $request->user()->id));

            return Response::jsonError('很抱歉，支付方案维护中！');
        }

        // 檢查方案是否允許使用以下支付渠道
        if (!in_array($payment_id, $plan->gateway_ids)) {
            Log::warning(sprintf('用戶 %s 嘗試調用支付方案不支援的渠道', $request->user()->id));

            return Response::jsonError('很抱歉，支付渠道维护中！');
        }

        try {
            $payment = Payment::where('status', 1)->findOrFail($payment_id);
        } catch (\Exception $e) {
            Log::warning(sprintf('用戶 %s 嘗試調用未開放的支付渠道', $request->user()->id));

            return Response::jsonError('很抱歉，支付渠道维护中！');
        }

        // 獲取渠道限額
        $daily_total = Gateway::getDailyLimit($payment_id);
        $estimated_amount = $plan->price + $daily_total;

        // 檢查渠道今日限額
        if ($estimated_amount > $payment->daily_limit) {
            Log::notice(sprintf('渠道 %s 已臨界每日限額', $payment_id));

            return Response::jsonError('很抱歉，支付渠道维护中！');
        }

        // 請求支付
        try {
            $response = $payment->initGateway()->pay($plan);
            Cache::increment($cache_key);
        } catch (\Exception $e) {
            Log::error(sprintf('調用 %s (%s) 支付接口錯誤：%s', $payment->name, $payment->id, $e->getMessage()));

            return Response::jsonError('很抱歉，支付渠道维护中！');
        }

        return Response::jsonSuccess(__('api.success'), $response);
    }

    // 支付結果回調
    public function callback(Request $request)
    {
        $order_no = $request->get('order_no') ?? '';

        try {
            // 查詢訂單是否存在
            $order = Order::orderNo($order_no)->firstOrFail();
        } catch (\Exception $e) {
            Log::warning(sprintf('渠道试图回调不存在的订单 %s, 请求源 %s', $order_no, $request->url()));

            return Response::jsonError('订单已回调或不存在！');
        }

        // 不允許重複回調
        if ($order->status != 0) {
            Log::warning(sprintf('渠道试图重複回调订单 %s, 请求源 %s', $order_no, $request->url()));

            return Response::jsonError('订单已回调或不存在！');
        }

        // 調用支付渠道 SDK
        $gateway = $order->payment->initGateway();

        // 驗證簽名
        $valid = $gateway->checkSign($request->post());

        if (!$valid) {
            return Response::jsonError('签名验证失败！');
        }

        // 不同渠道返回格式不同
        $response = $gateway->updateOrder($order, $request->post());

        // 添加每日限額
        Gateway::incDailyLimit($order->payment_id, $order->amount);

        // 建立財報紀錄
        RechargeJob::dispatch($order);

        return  $response;
    }

    public function mockCallback(Request $request)
    {
        if (config('app.env') == 'local') {
            $order_no = $request->get('order_no') ?? '';

            $order = Order::orderNo($order_no)->firstOrFail();

            // 調用支付渠道 SDK
            $gateway = $order->payment->initGateway();

            $response = $gateway->mockCallback($order);

            return $response;
        }

        return '';
    }
}
