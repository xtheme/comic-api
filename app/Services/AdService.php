<?php

namespace App\Services;

use App\Models\Ad;
use Illuminate\Support\Facades\Cache;

class AdService
{
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
        $platform = strtolower(request()->header('platform'));

        $key = sprintf('ad:type:%s-%s', $space_id, $platform);

        return Cache::remember($key, 300, function () use ($space_id, $platform) {
            $where = [
                'space_id' => $space_id,
                'status' => 1,
                'platform' => $platform,
            ];

            $ad = Ad::where($where)->orderByDesc('sort')->orderByDesc('id')->get()->first();

            return $ad ? $ad->toArray() : null;
        });
    }
}
