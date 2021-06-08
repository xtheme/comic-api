<?php

namespace App\Repositories;

use App\Models\AdSpace;
use App\Repositories\Contracts\AdSpaceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdSpaceRepository extends Repository implements AdSpaceRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    public function model(): string
    {
        return AdSpace::class;
    }

    public function filter(Request $request): Builder
    {
        $id = $request->get('id') ?? '';
        $name = $request->get('name') ?? '';
        $class = $request->get('class') ?? '';

        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'DESC';

        return $this->model::when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($name, function (Builder $query, $name) {
            return $query->where('name', $name);
        })->when($class, function (Builder $query, $class) {
            return $query->where('class', $class);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }

    /**
     * 查询廣告位底下的廣告列表
     */
    public function ads(Request $request, $id): Builder
    {
        $platform = $request->header('platform');

        return $this->model::with([
            'ads' => function ($query) use ($platform) {
                return $query->whereIn('platform', [$platform , '-1'])->orderByDesc('sort');
            },
        ])->when($id, function (Builder $query, $id) {
            return $query->where('id', $id)->orWhere('name', $id);
        })->where('status', 1);
    }

}
