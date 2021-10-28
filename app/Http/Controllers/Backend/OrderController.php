<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OrderOptions;
use App\Http\Controllers\Controller;
use App\Jobs\RechargeJob;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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
            'type_options' => OrderOptions::TYPE_OPTIONS,
            'status_options' => OrderOptions::STATUS_OPTIONS,
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.order.index')->with($data);
    }

    public function detail($order_id)
    {
        $data = [
            'order' => $this->repository->find($order_id),
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
