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
        $raw = Navigation::where('status', 1)->orderByDesc('sort')->get();

        $data = $raw->map(function ($nav) use ($request) {
            return [
                'id' => $nav->id,
                'title' => $nav->title,
                'icon' => $nav->icon,
                'uri' => $nav->uri,
                'target' => $nav->getRawOriginal('target'),
            ];
        })->toArray();

        return Response::jsonSuccess('返回成功', $data);
    }
}
