<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NavigationController extends Controller
{
    public function list(Request $request)
    {
        $raw = Navigation::with(['filter'])->where('status', 1)->orderByDesc('sort')->get();

        $data = $raw->map(function ($nav) use ($request) {
            $filter = [];

            // 篩選器
            if ($nav->filter_id != 0) {
                $filter = $nav->filter->params;

                foreach ($filter as $key => $val) {
                    if (!$val) unset($filter[$key]);
                }

                foreach ($nav->filter->tags as $type => $tag_arr) {
                    $filter['tags[' . $type . ']'] = implode(',', $tag_arr);
                }
            }

            return [
                'id' => $nav->id,
                'title' => $nav->title,
                'icon' => asset($nav->icon),
                'target' => $nav->getRawOriginal('target'),
                'link' => $nav->link,
                'filter' => $filter,
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
