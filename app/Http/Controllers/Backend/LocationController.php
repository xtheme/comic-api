<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChinaArea;
use App\Models\ChinaCity;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    public function cities($province_id = 0)
    {
        $cache_key = 'china:cities:' . $province_id;

        return Cache::remember($cache_key, 28800, function () use ($province_id) {
            $cities = ChinaCity::where('province_id', $province_id)->get();

            return $cities->mapWithKeys(function ($row) {
                return [$row->city_id => $row->city_name];
            });
        });
    }

    public function areas($city_id = 0)
    {
        $cache_key = 'china:areas:' . $city_id;

        return Cache::remember($cache_key, 28800, function () use ($city_id) {
            $areas = ChinaArea::where('city_id', $city_id)->get();

            return $areas->mapWithKeys(function ($row) {
                return [$row->area_id => $row->area_name];
            });
        });
    }
}
