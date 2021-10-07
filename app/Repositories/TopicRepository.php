<?php

namespace App\Repositories;

use App\Models\Topic;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TopicRepository extends Repository implements TopicRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Topic::class;
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
        $title = $request->input('title') ?? '';
        $causer = $request->input('causer') ?? '';
        $status = $request->input('status') ?? '';

        $order = $request->input('order') ?? 'sort';
        $sort = $request->input('sort') ?? 'desc';

        return $this->model::when($title, function (Builder $query, $title) {
            return $query->where('title', 'like' , '%' . $title . '%' );
        })->when($causer, function (Builder $query, $causer) {
            return $query->where('causer', $causer);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }
}
