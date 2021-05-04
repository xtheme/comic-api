<?php

namespace App\Repositories;

use App\Models\Block;
use App\Repositories\Contracts\BlockRepositoryInterface;
use Conner\Tagging\Model\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
        $title = $request->get('title') ?? '';
        $causer = $request->get('causer') ?? '';

        $order = $request->get('order') ?? 'sort';
        $sort = $request->get('sort') ?? 'asc';

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

    public function getTags(): ?Collection
    {
        return Tag::where('tag_group_id', 1)->where('suggest', 1)->orderByDesc('priority')->get();
    }
}
