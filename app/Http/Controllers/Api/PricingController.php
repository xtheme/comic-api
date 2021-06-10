<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PricingPackage;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Vinkla\Hashids\Facades\Hashids;

class PricingController extends Controller
{
    public function list(Request $request)
    {
        $status = $request->user->orders->where('status', 1)->count() ? 2 : 1;

        $data = PricingPackage::whereIn('status', [0 , $status])->orderByDesc('sort')->get();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function testCreateAccount(Request $request)
    {
        $data = app(PaymentService::class)->getAccountParams($request);

        return Response::json($data);
    }

    public function testBalanceTransfer(Request $request)
    {
        $data = app(PaymentService::class)->getTransferParams($request);

        return Response::json($data);
    }

    public function url(Request $request, $id)
    {
        $paymentService = new PaymentService();

        // 金流方注册
        if (!$paymentService->createAccount($request)) {
            return Response::jsonError('金流方注册失败！');
        }

        // 查询会员套餐
        $package = PricingPackage::find($id);

        if (!$package) {
            return Response::jsonError('会员套餐不存在！');
        }

        // 生成订单
        $inputs = [
            'user_id'     => $request->user->id,
            'mobile'      => $request->user->mobile ? sprintf('%s-%s', $request->user->area, $request->user->mobile) : '',
            'type'        => $package['type'],
            'name'        => $package['name'],
            'label'       => $package['label'],
            'days'        => $package['days'],
            'amount'      => $package['price'],
            'ip'          => $request->header('ip'),
            'platform'    => $request->header('platform'),
            'app_version' => $request->header('app-version'),
        ];

        $order = Order::create($inputs);

        // todo change config
        $domain = getOldConfig('web_config', 'pay_cashier_url');

        $params = [
            'name'      => $request->user->username, // 用户昵称
            'userId'    => $order->user_id, // 用戶
            'price'     => $order->amount, // 金额
            'orderId'   => $order->id, // 訂單號
            'desc'      => sprintf('%s - %s (%s)', $order->type, $order->name, $order->label),
            'gameId'    => getOldConfig('web_config', 'pay_game_id'), // todo change config
            'userAgent' => $request->header('platform'),
        ];
        Log::debug(json_encode($params));
        // newPay?userId=xxx&name=xxx&price=100&desc=xxxx&orderId=xxx&userAgent=1&gameId=xxx

        $data = [
            'order_id' => $order->id,
            'hash_id' => Hashids::encode($order->id),
            'url' => $domain . http_build_query($params),
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
