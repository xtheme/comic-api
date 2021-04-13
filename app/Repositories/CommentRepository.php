<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommentRepository extends Repository implements CommentRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Comment::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        // 预设查询30天内的数据, 减少查询时间
        $few_days_ago = Carbon::parse('-30 days')->setTimeFromTimeString('00:00:00')->toDateTimeString();
        $today = Carbon::now()->toDateTimeString();
        $time_period = sprintf('%s - %s', $few_days_ago, $today);

        $username = $request->get('username') ?? '';
        $id = $request->get('id') ?? '';
        $status = $request->get('status') ?? '';
        $date_register = $request->get('date_register') ?? '';

        return $this->model::with(['user' , 'bookchapter' , 'bookchapter.book'])
            ->when($username, function (Builder $query, $username) {
                $query->whereHas('user', function (Builder $query) use ($username) {
                    return $query->where('username', 'like', '%' . $username . '%');
                });
            })->when($id, function (Builder $query, $id) {
                $query->whereHas('bookchapter.book', function (Builder $query) use ($id) {
                    return $query->where('id', '=', $id)->orWhere('book_name' , 'like' , '%' . $id . '%');
                });
            })->when($status, function (Builder $query, $status) {
                return $query->where('status', $status);
            })->when($date_register, function (Builder $query, $date_register) {
                $date = explode(' - ', $date_register);
                $start_date = strtotime($date[0] . ' 00:00:00');
                $end_date = strtotime($date[1] . ' 23:59:59');
                return $query->whereBetween('createtime', [
                    $start_date,
                    $end_date,
                ]);
            })->orderByDesc('createtime');
    }
}
