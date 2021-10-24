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
            return [
                'id' => $nav->id,
                'title' => $nav->title,
                'icon' => asset($nav->icon),
                'target' => $nav->getRawOriginal('target'),
                'link' => $nav->link,
                'filter_id' => $nav->filter_id,
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
