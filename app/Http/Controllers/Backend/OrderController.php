<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OrderOptions;
use App\Http\Controllers\Controller;
use App\Jobs\RechargeJob;
use App\Models\Order;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    private function filter(Request $request): Builder
    {
        $order_no = $request->get('order_no') ?? '';
        $transaction_id = $request->get('transaction_id') ?? '';
        $user_id = $request->get('user_id') ?? '';
        $type = $request->get('type') ?? '';
        $status = $request->get('status') ?? null;
        $platform = $request->get('platform') ?? 0;
        $version = $request->get('version') ?? 0;
        $created_at = $request->get('created_at') ?? '';

        return Order::has('user')->with(['user', 'payment'])->when($order_no, function (Builder $query, $order_no) {
            return $query->where('order_no', $order_no);
        })->when($transaction_id, function (Builder $query, $transaction_id) {
            return $query->where('transaction_id', $transaction_id);
        })->when($user_id, function (Builder $query, $user_id) {
            return $query->where('user_id', $user_id);
        })->when($type, function (Builder $query, $type) {
            return $query->where('type', $type);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($platform, function (Builder $query, $platform) {
            return $query->where('platform', $platform);
        })->when($version, function (Builder $query, $version) {
            return $query->where('version', $version);
        })->when($created_at, function (Builder $query, $created_at) {
            $date = explode(' - ', $created_at);
            $start_date = $date[0];
            $end_date = $date[1];

            return $query->whereBetween('created_at', [
                $start_date,
                $end_date,
            ]);
        })->latest();
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->filter($request)->paginate(),
            'type_options' => OrderOptions::TYPE_OPTIONS,
            'status_options' => OrderOptions::STATUS_OPTIONS,
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.order.index')->with($data);
    }

    public function detail($order_id)
    {
        $data = [
            'order' => Order::findOrFail($order_id),
        ];

        return view('backend.order.detail')->with($data);
    }

    // 手動上分
    public function callback($order_id)
    {
        $order = Order::findOrFail($order_id);

        DB::transaction(function () use ($order) {
            app(UserService::class)->manualUpdateOrder($order);
        });

        // 建立財報紀錄
        RechargeJob::dispatch($order);

        return Response::jsonSuccess('回调订单完成！');
    }
}
