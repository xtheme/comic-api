<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PayRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pricing;
use App\Services\PaymentService;
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
        // $newcomer = $request->user->orders->where('status', 1)->exists();

        // 所有啟用的支付方案
        $pricing = Pricing::where('status', 1)->orderBy('type')->orderByDesc('sort')->get();

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
                    // 'target' => $gateway->button_target,
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
            // status=0 不允許重複回調
            $order = Order::orderNo($order_no)->where('status', 0)->firstOrFail();
        } catch (\Exception $e) {
            Log::warning(sprintf('異常回調訂單 %s', $order_no));
            return 'error';
        }

        // 不同渠道返回格式不同
        $gateway = $order->gateway;
        $payment_service = app(PaymentService::class);
        return $payment_service->init($gateway)->callback($request->post());
    }

    // 獲取渠道限額
    public function getGatewayDaily($gateway_id)
    {
        $redis_key = sprintf('payment:gateway:%s:%s', $gateway_id, date('Y-m-d'));

        $cache_limit = 0;

        if (Cache::has($redis_key)) {
            $cache_limit = Cache::get($redis_key);
        }

        return $cache_limit;
    }

    /**
     * 存款成功回调接口 /api/balance_transfer
     */
    // public function balanceTransfer(Request $request)
    // {
    //     $params = [
    //         'customerId' => $request->input('param.customerId'),
    //         'referenceId' => $request->input('param.referenceId'),
    //         'gameId' => $request->input('param.gameId'),
    //         'amount' => $request->input('param.amount'),
    //         'opType' => $request->input('param.opType'),
    //         'timestamp' => $request->input('param.timestamp'),
    //     ];
    //     // return Response::jsonSuccess(__('api.success'), $params);
    //     $sign = $request->input('sign');
    //
    //     // 校验签名
    //     $paymentService = new PaymentService();
    //     $valid_sign = $paymentService->getSign($params);
    //     if ($valid_sign != $sign) {
    //         return Response::jsonError('签名不合法！');
    //     }
    //
    //     $order = Order::findOrFail($params['referenceId']);
    //
    //     $response = [
    //         'customerId' => $order->user_id,
    //         'balance' => 0,
    //     ];
    //
    //     $error = false;
    //
    //     // 校验订单数据
    //     if ($order->status != 0) {
    //         Log::debug('订单已回调，请勿重复操作，status=' . $order->status);
    //         $error = true;
    //     }
    //
    //     if ($params['customerId'] != $order->user_id) {
    //         Log::debug('订单号与用户无法匹配，customerId=' . $params['customerId'] . '，user_id=' . $params['customerId']);
    //         $error = true;
    //     }
    //
    //     if ($params['opType'] != '0') {
    //         Log::debug('操作类型只允许转入，opType=' . $params['opType']);
    //         $error = true;
    //     }
    //
    //     if (!$error) {
    //         // 更新订单数据
    //         $update = [
    //             'status' => 1,
    //             'transaction_at' => date('Y-m-d H:i:s'),
    //         ];
    //
    //         $order->update($update);
    //
    //         // 更新用户 subscribed_at
    //         $user = User::find($order->user_id);
    //
    //         if ($user) {
    //             if ($user->subscribed_at && $user->subscribed_at->greaterThan(Carbon::now())) {
    //                 $user->subscribed_at = $user->subscribed_at->addDays($order->days);
    //             } else {
    //                 $user->subscribed_at = Carbon::now()->addDays($order->days);
    //             }
    //
    //             $user->save();
    //
    //             activity()->useLog('API')->performedOn($user)->withProperties($user->getChanges())->log('充值成功回调');
    //         }
    //     }
    //
    //     return Response::jsonSuccess('请求成功！', $response);
    // }

    /**
     * 查询未支付订单接口 /api/order
     */
    // public function orderInfo(Request $request)
    // {
    //     $game_id = $request->input('gameId');
    //     $order_id = $request->input('orderNo');
    //
    //     // todo change config
    //     $pay_game_id = getOldConfig('web_config', 'pay_game_id');
    //     // $pay_game_id = getConfig('payment', 'game_id');
    //
    //     if ($game_id != $pay_game_id) {
    //         return Response::jsonError('游戏错误！');
    //     }
    //
    //     $order = Order::find($order_id);
    //
    //     if ($order) {
    //         if ($order->status == 1) {
    //             return response()->json([
    //                 'code' => 404,
    //                 'name' => 'Not Found',
    //                 'message' => '订单已支付',
    //                 'data' => new \stdClass,
    //             ]);
    //         }
    //
    //         return response()->json([
    //             'code' => 200,
    //             'name' => 'OK',
    //             'message' => 'success',
    //             'data' => [
    //                 'name' => $order->user->username,
    //                 'userId' => $order->user_id,
    //                 'price' => $order->amount,
    //                 'orderId' => $order->id,
    //                 'desc' => sprintf('%s (%s)', $order->type, $order->name),
    //                 'userAgent' => '',
    //                 'gameId' => $pay_game_id,
    //             ],
    //         ]);
    //     }
    //
    //     return response()->json([
    //         'code' => 404,
    //         'name' => 'Not Found',
    //         'message' => '订单不存在',
    //         'data' => new \stdClass,
    //     ]);
    // }
}
