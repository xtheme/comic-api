<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResumeOptions;
use App\Models\ChinaArea;
use App\Models\ChinaCity;
use App\Models\ChinaProvince;
use App\Models\Resume;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ResumeController extends BaseController
{
    public function provinces()
    {
        $collect = ChinaProvince::get(['province_id', 'province_name']);

        return Response::jsonSuccess(__('api.success'), $collect);
    }

    public function cities($province_id = null)
    {
        $collect = ChinaCity::when($province_id, function (Builder $query, $province_id) {
            return $query->where('province_id', $province_id);
        })->get(['city_id', 'city_name']);

        return Response::jsonSuccess(__('api.success'), $collect);
    }

    public function areas($city_id = null)
    {
        $collect = ChinaArea::when($city_id, function (Builder $query, $city_id) {
            return $query->where('city_id', $city_id);
        })->get(['area_id', 'area_name']);

        return Response::jsonSuccess(__('api.success'), $collect);
    }

    public function keywords()
    {
        $body_shape = ResumeOptions::BODY_SHAPE;
        $service_type = ResumeOptions::SERVICE_TYPE;
        $keywords = array_merge($body_shape, $service_type);

        return Response::jsonSuccess(__('api.success'), $keywords);
    }

    private function filter(Request $request): Builder
    {
        $province_id = $request->input('province_id') ?? null;
        $city_id = $request->input('city_id') ?? null;
        $area_id = $request->input('area_id') ?? null;
        $keywords = $request->input('keywords');

        $order = $request->input('order') ?? 'updated_at';
        $sort = $request->input('sort') ?? 'desc';
        $limit = $request->input('limit') ?? 10;

        return Resume::active()->when($province_id, function (Builder $query, $province_id) {
            return $query->where('province_id', $province_id);
        })->when($city_id, function (Builder $query, $city_id) {
            return $query->where('city_id', $city_id);
        })->when($area_id, function (Builder $query, $area_id) {
            return $query->where('area_id', $area_id);
        })->when($order, function (Builder $query, $order) use ($sort) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        })->withAllTags($keywords, 'resume')->take($limit);
    }

    // todo use api resource
    private function format(Resume $resume): array
    {
        $data = [
            'id' => $resume->id,
            'province_id' => $resume->province_id,
            'city_id' => $resume->city_id,
            'area_id' => $resume->area_id,
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

    public function list(Request $request)
    {
        $collect = $this->filter($request)->get();

        $data = $collect->map(function ($resume) {
            return $this->format($resume);
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function detail($id)
    {
        $resume = Resume::find($id);

        $data = $this->format($resume);

        // 訪問數+1
        // $resume->increment('view_counts');

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
