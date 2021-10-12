<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class OrderRepository extends Repository implements OrderRepositoryInterface
{
    private $cache_ttl = 120;

    /**
     * @return string
     */
    public function model(): string
    {
        return Order::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $order_no = $request->get('order_no') ?? '';
        $transaction_id = $request->get('transaction_id') ?? '';
        $user_id = $request->get('user_id') ?? '';
        $status = $request->get('status') ?? '';
        $platform = $request->get('platform') ?? 0;
        $version = $request->get('version') ?? 0;
        $created_at = $request->get('created_at') ?? '';

        return $this->model::has('user')->with(['user', 'payment'])->when($order_no, function (Builder $query, $order_no) {
                return $query->where('order_no', $order_no);
            })->when($transaction_id, function (Builder $query, $transaction_id) {
                return $query->where('transaction_id', $transaction_id);
            })->when($user_id, function (Builder $query, $user_id) {
                return $query->where('user_id', $user_id);
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

    public function orders_count(Request $request): int
    {
        return $this->filter($request)->count();
        // return Order::count();
    }

    // public function success_orders_count(Request $request): int
    // {
    //     return $this->filter($request)->where('status', 1)->count();
    //     // return Order::whereStatus(1)->count();
    // }

    public function orders_amount(Request $request): string
    {
        $amount = $this->filter($request)->sum('amount');

        // $amount = Order::whereStatus(1)->sum('amount');
        return number_format($amount);
    }

    public function renew_orders_count(Request $request): int
    {
        return $this->filter($request)->where('first', 0)->count();
        // return Order::whereStatus(1)->whereFirst(0)->count();

    }

    public function renew_orders_amount(Request $request): string
    {
        $amount = $this->filter($request)->where('first', 0)->sum('amount');

        // $amount = Order::whereStatus(1)->whereFirst(0)->sum('amount');
        return number_format($amount);
    }
}
