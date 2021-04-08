<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $builderQuery;

    public function __construct(Builder $builderQuery)
    {
        $this->builderQuery = $builderQuery;
    }

    public function headings(): array
    {
        return [
            '订单号',
            '付款金额',
            '套餐类型',
            '套餐标题',
            '用户ID',
            'IP',
            '购买次数',
            '充值成功次数',
            '手机系统',
            '版本号',
            '注册时间',
            '订单创建时间',
            '更新时间',
            '订单状态',
        ];
    }

    public function query()
    {
        return $this->builderQuery;
    }

    public function map($order): array
    {
        // Mapping above method headings
        return [
            $order->id,
            $order->amount,
            $order->type,
            $order->name,
            $order->user_id,
            $order->ip,
            $order->user->orders_count->count ?? '0',
            $order->user->orders_success_count->count ?? '0',
            $order->platform == 1 ? '安卓' : '苹果',
            $order->app_version,
            $order->user->created_at,
            $order->created_at,
            $order->updated_at,
            $order->status == 1 ? '已付款' : '',
        ];
    }
}
