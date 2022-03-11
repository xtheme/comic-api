<?php

namespace App\Http\Controllers\Api;

use App\Models\Resume;
use App\Models\ResumeCity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ResumeController extends BaseController
{
    public function cities(Request $request)
    {
        $collect = ResumeCity::with(['children', 'children.children'])->where('p_id', 0)->get();

        return Response::jsonSuccess(__('api.success'), $collect);
    }

    private function filter(Request $request): Builder
    {
        $city_id = $request->input('city_id') ?? null;

        $order = $request->input('order') ?? 'updated_at';
        $sort = $request->input('sort') ?? 'desc';
        $limit = $request->input('limit') ?? 10;

        return Resume::when($city_id, function (Builder $query, $city_id) {
            return $query->where('city_id', $city_id);
        })->when($order, function (Builder $query, $order) use ($sort) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        })->where('status', 1)->take($limit);
    }

    // todo use api resource
    private function format(Resume $resume): array
    {
        $data = [
            'id' => $resume->id,
            'nickname' => $resume->nickname,
            'age' => $resume->age,
            'cup' => $resume->cup,
            'body_shape' => $resume->body_shape,
            'service' => $resume->service,
            'price' => $resume->price,
            'qq' => $resume->qq,
            'wechat' => $resume->wechat,
            'phone' => $resume->phone,
            'cover' => $resume->cover,
            'profile' => $resume->profile,
        ];;

        return $data;
    }

    public function list(Request $request, $city = null)
    {
        $request->merge([
            'state' => 1,
            'city_id' => $city,
            'order' => 'created_at',
            'sort' => 'desc',
        ]);

        $collect = $this->filter($request)->get();

        $data = $collect->map(function ($video) {
            return $this->format($video);
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function detail($id)
    {
        $video = Movie::find($id);

        $data = $this->format($video);

        // 訪問數+1
        $video->increment('views');

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
