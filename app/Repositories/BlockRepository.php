<?php

namespace App\Repositories;

use App\Models\Block;
use App\Repositories\Contracts\BlockRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlockRepository extends Repository implements BlockRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Block::class;
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

        $order = $request->input('order') ?? 'sort';
        $sort = $request->input('sort') ?? 'asc';

        return $this->model::when($title, function (Builder $query, $title) {
            return $query->where('title', 'like' , '%' . $title . '%' );
        })->when($causer, function (Builder $query, $causer) {

            if ($causer == 'video'){
                $causer = 'App\Models\Video';
            }else{
                $causer = 'App\Models\Block';
            }

            return $query->where('causer', $causer);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }
}
