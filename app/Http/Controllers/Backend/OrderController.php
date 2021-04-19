<?php

namespace App\Http\Controllers\Backend;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    private $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 订单列表
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
    {
        $data = [
            'list' => $this->repository->filter($request)->paginate(),
            'status_options' => ['1' => '未付款', '2' => '已付款'],
            'orders_count' => $this->repository->orders_count(),
            'success_orders_count' => $this->repository->success_orders_count(),
            'orders_amount' => $this->repository->orders_amount(),
            'renew_orders_count' => $this->repository->renew_orders_count(),
            'renew_orders_amount' => $this->repository->renew_orders_amount(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.order.index')->with($data);
    }

    /**
     * 汇出订单
     *
     * @param  Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(Request $request)
    {
        $query = $this->repository->filter($request);

        return Excel::download(new OrdersExport($query), 'orders-' . date('Y-m-d') . '.xlsx');
    }
}