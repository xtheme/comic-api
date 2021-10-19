<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Record;

class VideoController extends BaseController
{
    public function __construct(Request $request)
    {
        if ($request->bearerToken()) {
            $this->middleware('auth:sanctum');
        }
    }

    public function detail($id)
    {
        $data = Video::with([
            'series' => function ($query) {
                return $query->where('status', 1)->orderBy('episode');
            },
            'series.cdn' => function ($query) {
                return $query->where('status', 1);
            },
        ])->withCount(['visit_histories', 'play_histories'])->find($id)->toArray();

        // 數字格式化
        $data['visit_counts'] = shortenNumber($data['visit_histories_count']);
        $data['play_counts'] = (request()->header('platform') == 1) ? $data['play_histories_count'] : shortenNumber($data['play_histories_count']);

        // todo 訪問數+1
        Record::from('video')->visit($id);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    // 紀錄點擊播放
    public function play($id, $series_id)
    {
        // todo 訪問數+1
        Record::from('video')->play($id, $series_id);

        return Response::jsonSuccess(__('api.success'));
    }

    // 猜你喜歡
    public function recommend($id = null)
    {
        $limit = 4;
        $tags = [];

        if ($id) {
            $video = Video::findOrFail($id);
            $tags = $video->tagged_tags;
        }

        if ($tags) {
            $videos = Video::select(['id', 'title', 'cover'])->withCount(['visit_histories'])->withAnyTag($tags)->where('id', '!=', $id)->inRandomOrder()->limit($limit)->get();
        } else {
            $videos = Video::select(['id', 'title', 'cover'])->withCount(['visit_histories'])->inRandomOrder()->limit($limit)->get();
        }

        $data = $videos->map(function ($video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                // 'author' => $video->author,
                // 'description' => $video->description,
                'cover' => $video->cover,
                'tagged_tags' => $video->tagged_tags,
                'visit_counts' => shortenNumber($video->visit_histories_count),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
