<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Contracts\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookRepository extends Repository implements BookRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Book::class;
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

        $title = $request->get('title') ?? '';
        $model_id = $request->get('type') ?? '';
        $nickname = $request->get('nickname') ?? '';
        $status = $request->get('status') ?? '';
        $audit_status = $request->get('audit_status') ?? '';
        $created_at = $request->get('created_at') ?? '';

        $order = $request->get('order') ?? 'created_at';
        $sort = $request->get('sort') ?? 'DESC';

        // 替換 created_at
        if (!$request->has('created_at') && !$request->has('page')) {
            $created_at = $time_period;
            $request->merge([
                'created_at' => $created_at,
            ]);
        }

        return $this->model::with(['article', 'user', 'fakeUser', 'auditRecord'])
            ->whereHas('article', function (Builder $query) use ($title, $model_id) {
                $query->when($title, function (Builder $query, $title) {
                    return $query->where('title', 'like', '%' . $title . '%');
                });
                $query->when($model_id, function (Builder $query, $model_id) {
                    return $query->where('model_id', $model_id);
                });
            })->when($nickname, function (Builder $query, $nickname) {
                $query->whereHas('user', function (Builder $query) use ($nickname) {
                    return $query->where('nickname', 'like', '%' . $nickname . '%');
                })->orWhereHas('fakeUser', function (Builder $query) use ($nickname) {
                    return $query->where('nickname', 'like', '%' . $nickname . '%');
                });
            })->when($audit_status, function (Builder $query, $audit_status) {
                $query->whereHas('auditRecord', function (Builder $query) use ($audit_status) {
                    return $query->where('action', $audit_status - 1);
                });
            })->when($status, function (Builder $query, $status) {
                return $query->where('status', $status);
            })->when($created_at, function (Builder $query, $created_at) {
                $date = explode(' - ', $created_at);
                $start_date = $date[0];
                $end_date = $date[1];
                return $query->whereBetween('created_at', [
                    $start_date,
                    $end_date,
                ]);
            })->when($sort, function (Builder $query, $sort) use ($order) {
                if ($sort == 'DESC') {
                    return $query->orderByDesc($order);
                } else {
                    return $query->orderBy($order);
                }
            })->orderByDesc('id');
    }
}
