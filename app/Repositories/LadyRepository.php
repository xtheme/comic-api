<?php

namespace App\Repositories;

use App\Models\Lady;
use App\Repositories\Contracts\LadyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LadyRepository extends Repository implements LadyRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Lady::class;
    }

    public function find($id): ?Model
    {
        return $this->model->findOrFail($id);
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
        $city_id = $request->input('city_id') ?? null;
        $state = $request->input('state') ?? '';
        $order = $request->input('order') ?? 'updated_at';
        $sort = $request->input('sort') ?? 'desc';
        $limit = $request->input('limit') ?? 10;

        return $this->model::when($city_id, function (Builder $query, $city_id) {
            return $query->where('city_id', $city_id);
        })->when($state, function (Builder $query, $state) {
            return $query->where('state', $state);
        })->when($order, function (Builder $query, $order) use ($sort) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        })->take($limit);
    }

    public function format(Model $lady): array
    {
        $data =  [
            'id' => $lady->id,
            'name' => $lady->name,
            'city_id' => $lady->city_id,
            'age' => $lady->age,
            'sale_price' => $lady->sale_price,
            'price' => $lady->price,
            'qq' => $lady->qq,
            'wechat' => $lady->wechat,
            'phone' => $lady->phone,
            'tag' => $lady->tag,
            'profile' => $lady->profile,
            'pictures' => $lady->pictures,
            'updated_at' => $lady->updated_at->diffForHumans(),
        ];;

        return $data;
    }

    public function collectFormat(Collection $collect): array
    {
        $data = $collect->map(function($video) {
            return $this->format($video);
        })->toArray();

        return $data;
    }
}
