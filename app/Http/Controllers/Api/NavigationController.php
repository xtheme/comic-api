<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class NavigationController extends Controller
{
    public function list()
    {
        $cache_key = 'navigation';

        $data = Cache::remember($cache_key, 600, function () {
            $raw = Navigation::with(['filter'])->where('status', 1)->orderByDesc('sort')->get();

            return $raw->map(function ($nav) {
                return [
                    'id' => $nav->id,
                    'title' => $nav->title,
                    'icon' => asset($nav->icon),
                    'target' => $nav->getRawOriginal('target'),
                    'link' => $nav->link,
                    'filter_id' => $nav->filter_id,
                ];
            })->toArray();
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
