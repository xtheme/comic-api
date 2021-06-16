<?php

namespace App\Http\Controllers\Backend;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    private $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'status_options' => ['1' => '未付款', '2' => '已付款'],
            'orders_count' => $this->repository->orders_count($request),
            // 'success_orders_count' => $this->repository->success_orders_count($request),
            'orders_amount' => $this->repository->orders_amount($request),
            'renew_orders_count' => $this->repository->renew_orders_count($request),
            'renew_orders_amount' => $this->repository->renew_orders_amount($request),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.order.index')->with($data);
    }

    public function export(Request $request)
    {
        $query = $this->repository->filter($request);

        return Excel::download(new OrdersExport($query), 'orders-' . date('Y-m-d') . '.xlsx');
    }

    public function callback($id)
    {
        $order = Order::findOrFail($id);

        // 更新订单数据
        $update = [
            'status' => 1,
            'transaction_at' => date('Y-m-d H:i:s'),
        ];

        $order->update($update);

        activity()->useLog('后台')->causedBy(auth()->user())->performedOn($order)->withProperties($order->getChanges())->log('回调订单为已付款');

        // 更新用户 subscribed_at
        $user = User::find($order->user_id);

        if ($user) {
            if ($user->subscribed_at && $user->subscribed_at->greaterThan(Carbon::now())) {
                $user->subscribed_at = $user->subscribed_at->addDays($order->days);
            } else {
                $user->subscribed_at = Carbon::now()->addDays($order->days);
            }

            $user->save();

            activity()->useLog('后台')->causedBy(auth()->user())->performedOn($user)->withProperties($user->getChanges())->log('补发 VIP');
        }

        return Response::jsonSuccess('回调订单完成！');
    }
}
