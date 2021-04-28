<?php

namespace App\Repositories;

use App\Models\VideoAd;
use App\Repositories\Contracts\VideoAdRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VideoAdRepository extends Repository implements VideoAdRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return VideoAd::class;
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
        $name = $request->get('name') ?? '';
        $space_id = $request->get('space_id') ?? '';
        $jump_type = $request->get('jump_type') ?? '';
        $platform = $request->get('platform') ?? '';
        $status = $request->get('status') ?? '';
        $order = $request->get('order') ?? 'sort';
        $sort = $request->get('sort') ?? 'ASC';

        return $this->model::with(['video_ad_space'])->when($name, function (Builder $query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->when($space_id, function (Builder $query, $space_id) {
            return $query->where('space_id', $space_id);
        })->when($jump_type, function (Builder $query, $jump_type) {
            return $query->where('jump_type', $jump_type);
        })->when($platform, function (Builder $query, $platform) {
            return $query->where('platform', $platform);
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
