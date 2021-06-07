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
        $status = $request->get('status') ?? '';

        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'DESC';

        return $this->model::when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($name, function (Builder $query, $name) {
            return $query->where('name', $name);
        })->when($class, function (Builder $query, $class) {
            return $query->where('class', $class);
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
