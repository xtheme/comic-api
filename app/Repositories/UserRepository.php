<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return User::class;
    }

    /**
     * 查询DB
     *
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $id = $request->get('id') ?? '';
        $name = $request->get('name') ?? '';
        $channel_id = $request->get('channel_id') ?? '';
        $mobile = $request->get('mobile') ?? '';
        $is_active = $request->get('is_active') ?? '';
        $is_ban = $request->get('is_ban') ?? '';

        $subscribed = $request->get('subscribed') ?? '';
        $date_register = $request->get('date_register') ?? '';

        $order = $request->get('order') ?? 'created_at';
        $sort = $request->get('sort') ?? 'desc';

        return $this->model::with([])->withCount([])->when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($name, function (Builder $query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->when($channel_id, function (Builder $query, $channel_id) {
            return $query->where('channel_id', $channel_id);
        })->when($mobile, function (Builder $query, $mobile) {
            return $query->where('mobile', 'like', '%' . $mobile . '%');
        })->when($is_active, function (Builder $query, $is_active) {
            return $query->where('is_active', $is_active - 1);
        })->when($is_ban, function (Builder $query, $is_ban) {
            return $query->where('is_ban', $is_ban -1);
        })->when($subscribed, function (Builder $query, $subscribed) {
            if ($subscribed == 1) {
                return $query->whereDate('subscribed_until', '>=', Carbon::now());
            } else {
                return $query->whereDate('subscribed_until', '<', Carbon::now())->orWhereNull('subscribed_until');
            }
        })->when($date_register, function (Builder $query, $date_register) {
            $date = explode(' - ', $date_register);
            $start_date = $date[0] . ' 00:00:00';
            $end_date = $date[1] . ' 23:59:59';

            return $query->whereBetween('created_at', [$start_date, $end_date]);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }
}
