<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MovieController extends BaseController
{
    private function filter(Request $request): Builder
    {
        $country = $request->input('country') ?? null;
        $video_type = $request->input('video_type') ?? null;

        $status = $request->input('status') ?? '';
        $order = $request->input('order') ?? 'created_at';
        $sort = $request->input('sort') ?? 'desc';
        $limit = $request->input('limit') ?? 10;

        return Movie::when($country, function (Builder $query, $country) {
            return $query->where('country', $country);
        })->when($video_type, function (Builder $query, $video_type) {
            return $query->where('video_type', $video_type);
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($order, function (Builder $query, $order) use ($sort) {
            if ($order == 'random') {
                return $query->inRandomOrder();
            }

            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        })->take($limit);
    }

    // todo use api resource
    private function format(Movie $video): array
    {
        $data =  [
            'id' => $video->id,
            'title' => $video->title,
            'number' => $video->number,
            'producer' => $video->producer,
            'actor' => $video->actor,
            'views' => shortenNumber($video->views),
            'country' => $video->country,
            'subtitle' => $video->subtitle,
            'url' => $video->hls_url,
            'thumb' => $video->thumb,
            'tags' => $video->tags,
            'updated_at' => $video->updated_at->diffForHumans(),
        ];;

        return $data;
    }

    public function list(Request $request, $type)
    {
        switch ($type) {
            // 最新
            case 'latest':
                $request->merge([
                    'status' => 1,
                    'order' => 'created_at',
                    'sort' => 'desc',
                ]);
                break;
            // 熱門
            case 'popular':
                $request->merge([
                    'status' => 1,
                    'order' => 'views',
                    'sort' => 'desc',
                ]);
                break;
            // 隨機推薦
            case 'recommend':
                $request->merge([
                    'status' => 1,
                    'order' => 'random',
                ]);
                break;
        }

        $collect = $this->filter($request)->get();

        $data = $collect->map(function($video) {
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
