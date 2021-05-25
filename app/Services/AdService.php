<?php

namespace App\Services;

use App\Models\Ad;
use App\Traits\CacheTrait;
use Illuminate\Support\Facades\Cache;

class AdService
{
    use CacheTrait;

    // 插入广告
    public function insertAd($space_id, array $list = [])
    {
        $ad = $this->getAdBySpace($space_id);

        if ($ad) {
            $list = insertArray($list, $ad);
        }

        return $list;
    }

    public function getAdBySpace($space_id)
    {
        $platform = request()->header('platform');

        $key = $this->getCacheKeyPrefix() . sprintf('ad:type:%s-%s', $space_id, $platform);

        return Cache::remember($key, $this->getRandomTtl(), function () use ($space_id, $platform) {
            $where = [
                'space_id'  => $space_id,
                'status'   => 1,
                'platform' => $platform,
            ];

            $ad = Ad::where($where)->orderByDesc('sort')->orderByDesc('id')->get()->first();

            return $ad ? $ad->toArray() : null;
        });
    }
}