<?php

namespace App\Repositories;

use App\Models\AdSpace;
use App\Repositories\Contracts\AdSpaceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdSpaceRepository extends Repository implements AdSpaceRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return AdSpace::class;
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
        $class = $request->get('class') ?? '';
        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'DESC';

        return $this->model::when($name, function (Builder $query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
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
     *
     * @param  name  $name 廣告位名稱
     *
     * @return Builder
     */
    public function getAdList($name): Builder
    {
        $platform = request()->header('platform');

        return $this->model::with(['video_ads' => function ($query) use ($platform){
            return $query->where('platform',$platform);
        }])->where([
            ['name', $name],
            ['status', 1]
        ]);

//        return AdSpace::with(['video_ads'])
//            ->whereHas('video_ads', function (Builder $query) use ($platform) {
//                return $query->where('platform',$platform);
//            })->where([
//                ['name', $name],
//                ['status', 1]
//            ]);
    }


}
