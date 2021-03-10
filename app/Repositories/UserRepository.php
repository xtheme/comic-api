<?php

namespace App\Repositories;

use App\Models\ClientUser;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return ClientUser::class;
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
        $nickname = $request->get('nickname') ?? '';
        $mobile = $request->get('mobile') ?? '';
        $status = $request->get('status') ?? '';
        $date_register = $request->get('date_register') ?? '';
        $date_login = $request->get('date_login') ?? '';
        $order = $request->get('order') ?? 'created_at';
        $sort = $request->get('sort') ?? 'DESC';

        return $this->model::withTrashed()->with(['likes', 'visitor_likes', 'collects', 'active', 'comments'])->withCount([
            'likes',
            'visitor_likes',
            'collects',
            'active',
            'comments',
        ])->when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($username, function (Builder $query, $username) {
            return $query->where('username', 'like', '%' . $username . '%');
        })->when($nickname, function (Builder $query, $nickname) {
            return $query->where('nickname', 'like', '%' . $nickname . '%');
        })->when($mobile, function (Builder $query, $mobile) {
            return $query->where('mobile', 'like', '%' . $mobile . '%');
        })->when($status, function (Builder $query, $status) {
            if ($status == 3) {
                return $query->whereNotNull('deleted_at');
            }

            return $query->where('status', '=', $status);
        })->when($date_register, function (Builder $query, $date_register) {
            $date = explode(' - ', $date_register);
            $start_date = $date[0] . ' 00:00:00';
            $end_date = $date[1] . ' 23:59:59';

            return $query->whereBetween('created_at', [$start_date, $end_date]);
        })->when($date_login, function (Builder $query, $date_login) {
            $date = explode(' - ', $date_login);
            $start_date = strtotime($date[0] . ' 00:00:00');
            $end_date = strtotime($date[1] . ' 23:59:59');

            return $query->whereBetween('logged_at', [$start_date, $end_date]);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }
}
