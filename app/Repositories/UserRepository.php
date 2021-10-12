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
        $username = $request->get('username') ?? '';
        $uuid = $request->get('uuid') ?? '';
        $mobile = $request->get('mobile') ?? '';
        $status = $request->get('status') ?? '';
        $version = $request->get('version') ?? '';
        $platform = $request->get('platform') ?? '';
        $subscribed = $request->get('subscribed') ?? '';
        $date_register = $request->get('date_register') ?? '';

        $order = $request->get('order') ?? 'created_at';
        $sort = $request->get('sort') ?? 'desc';

        return $this->model::with([])->withCount([])->when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($username, function (Builder $query, $username) {
            return $query->where('username', 'like', '%' . $username . '%');
        })->when($uuid, function (Builder $query, $uuid) {
            return $query->where('device_id', 'like', '%' . $uuid . '%');
        })->when($mobile, function (Builder $query, $mobile) {
            return $query->where('mobile', 'like', '%' . $mobile . '%');
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status - 1);
        })->when($version, function (Builder $query, $version) {
            return $query->where('version', $version );
        })->when($platform, function (Builder $query, $platform) {
            return $query->where('platform', $platform);
        })->when($subscribed, function (Builder $query, $subscribed) {
            if ($subscribed == 2) {
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
