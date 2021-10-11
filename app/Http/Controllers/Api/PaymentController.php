<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pricing;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

        $pricing = $pricing->mapToGroups(function($pack) {
            return [$pack->type => $pack];
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
            $redis_key = 'payment:gateway:' . $gateway->id;

            $cache_limit = 0;

            if (Cache::has($redis_key)) {
                $cache_limit = Cache::get($redis_key);
            }

            return $cache_limit >= $gateway->daily_limit;
        })->map(function ($gateway) {
            return [
                'gateway_id' => $gateway->id,
                'button' => $gateway->button,
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    // 調用支付
    public function pay(Request $request, $gateway_id)
    {
        $payment = Payment::where('status', 1)->findOrFail($gateway_id);

        $payment_service = app(PaymentService::class)->set($payment);

        return Response::jsonSuccess(__('api.success'), $payment_service->pay());
    }

    // 支付結果回調
    public function callback(Request $request, $gateway_id)
    {
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
