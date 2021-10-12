<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PayRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pricing;
use App\Services\PaymentService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class PaymentController extends Controller
{
    // 支付方案
    public function pricing(Request $request)
    {
        // todo 用戶是否為首存, 檢查當前用戶是否有支付成功的訂單
        $status = [0, 1];

        if (!app(UserService::class)->isFirstOrder(Auth::user()->id)) {
            $status = [0, 2];
        }

        // 所有啟用的支付方案
        $pricing = Pricing::whereIn('status', $status)->orderBy('type')->orderByDesc('sort')->get();

        $pricing = $pricing->mapToGroups(function($plan) {
            return [$plan->type => [
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
            ]];
        });

        return Response::jsonSuccess(__('api.success'), $pricing);
    }

    // 支付渠道
    public function gateway(Request $request, $pricing_id)
    {
        $gateways = Pricing::findOrFail($pricing_id)->gateways()->get();

        $data = $gateways->reject(function ($gateway) {
            // 排除停用渠道
            return $gateway->status == 0;
        })->reject(function ($gateway) {
            // 排除每日限額已達上限
            $daily_total = $this->getGatewayDaily($gateway->id);

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
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    // 調用支付
    public function pay(PayRequest $request)
    {
        $post = $request->validated();

        try {
            $plan = Pricing::where('status', 1)->findOrFail($post['plan_id']);
        } catch (\Exception $e) {
            Log::warning(sprintf('用戶 %s 嘗試調用未開放的支付方案', Auth::user()->id));
            return Response::jsonError('很抱歉，支付方案维护中！');
        }

        // 檢查方案是否允許使用以下支付渠道
        if (!in_array($post['gateway_id'], $plan->gateway_ids)) {
            Log::warning(sprintf('用戶 %s 嘗試調用支付方案不支援的渠道', Auth::user()->id));
            return Response::jsonError('很抱歉，支付渠道维护中！');
        }

        try {
            $gateway = Payment::where('status', 1)->findOrFail($post['gateway_id']);
        } catch (\Exception $e) {
            Log::warning(sprintf('用戶 %s 嘗試調用未開放的支付渠道', Auth::user()->id));
            return Response::jsonError('很抱歉，支付渠道维护中！');
        }

        // 檢查渠道今日限額
        $daily_total = $this->getGatewayDaily($post['gateway_id']);
        $estimated_amount = $plan->price + $daily_total;
        if ($estimated_amount > $gateway->daily_limit) {
            Log::notice(sprintf('渠道 %s 已臨界每日限額', $post['gateway_id']));
            return Response::jsonError('很抱歉，支付渠道维护中！');
        }

        // 請求支付
        try {
            $payment_service = app(PaymentService::class);
            $response = $payment_service->init($gateway)->pay($plan);
        } catch (\Exception $e) {
            Log::error(sprintf('調用 %s (%s) 支付接口錯誤：%s', $gateway->name, $gateway->id, $e->getMessage()));
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

        // 不同渠道返回格式不同
        return app(PaymentService::class)->init($order->gateway)->callback($order, $request->post());
    }

    // 獲取渠道限額
    // todo 可抽離到 Helpers/Functions
    public function getGatewayDaily($gateway_id)
    {
        $redis_key = sprintf('payment:gateway:%s:%s', $gateway_id, date('Y-m-d'));

        $cache_limit = 0;

        if (Cache::has($redis_key)) {
            $cache_limit = Cache::get($redis_key);
        }

        return $cache_limit;
    }

    public function mockCallback($order_id)
    {

    }
}
