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

        $id = $request->get('id') ?? '';
        $user_id = $request->get('user_id') ?? '';
        $status = $request->get('status') ?? '';
        $created_at = $request->get('created_at') ?? '';

        return $this->model::has('user')->with(['user', 'user.orders_count', 'user.orders_success_count'])
            ->when($id, function (Builder $query, $id) {
                return $query->where('id', $id);
            })->when($user_id, function (Builder $query, $user_id) {
                return $query->where('user_id', $user_id);
            })->when($status, function (Builder $query, $status) {
                return $query->where('status', $status - 1);
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

    public function orders_count(): int
    {
        $key = 'orders:count';

        return Cache::remember($key, $this->cache_ttl, function () {
            return Order::count();
        });
    }

    public function success_orders_count(): int
    {
        $key = 'orders:success_count';

        return Cache::remember($key, $this->cache_ttl, function () {
            return Order::whereStatus(1)->count();
        });
    }

    public function orders_amount(): string
    {
        $key = 'orders:total';

        return Cache::remember($key, $this->cache_ttl, function () {
            $amount = Order::whereStatus(1)->sum('amount');
            return number_format($amount);
        });
    }

    public function renew_orders_count(): int
    {
        $key = 'orders:renew_count';

        return Cache::remember($key, $this->cache_ttl, function () {
            return Order::whereStatus(1)->whereFirst(0)->count();
        });

    }

    public function renew_orders_amount(): string
    {
        $key = 'orders:renew_total';

        return Cache::remember($key, $this->cache_ttl, function () {
            $amount = Order::whereStatus(1)->whereFirst(0)->sum('amount');
            return number_format($amount);
        });
    }
}
