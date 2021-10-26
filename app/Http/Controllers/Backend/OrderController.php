<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OrderOptions;
// use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Jobs\RechargeJob;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
// use Maatwebsite\Excel\Facades\Excel;

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
            'platform_options' => OrderOptions::PLATFORM_OPTIONS,
            'status_options' => OrderOptions::STATUS_OPTIONS,
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.order.index')->with($data);
    }

    // 手動上分
    public function callback($id)
    {
        $order = Order::findOrFail($id);

        DB::transaction(function () use ($order) {
            app(UserService::class)->manualUpdateOrder($order);

            // 建立財報紀錄
            RechargeJob::dispatch($order);
        });

        return Response::jsonSuccess('回调订单完成！');
    }
}
