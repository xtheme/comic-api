<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
        try {
            $data = Video::withCount(['visit_logs', 'favorite_logs'])->findOrFail($id);

            // todo 數字格式化
            $data['visit_counts'] = shortenNumber($data['visit_logs_count']);
            $data['favorite_counts'] = shortenNumber($data['favorite_logs_count']);

            // todo 訪問數+1
            // $video->increment('view_counts');

            // todo 添加到排行榜

            return Response::jsonSuccess(__('api.success'), $data);
        } catch (\Exception $e) {
            return Response::jsonError($e->getMessage());
        }


    }

    // 猜你喜歡
    public function recommend($id = null)
    {
        $limit = 4;
        $tags = [];

        if ($id) {
            $video = Video::findOrFail($id);
            $tags = $video->tagged_tags;
            shuffle($tags);
            $tags = array_chunk($tags, 3)[0];
        }

        if ($tags) {
            $videos = Video::select(['id', 'title', 'cover'])->withCount(['visit_logs'])->withAnyTags($tags)->where('id', '!=', $id)->inRandomOrder()->limit($limit)->get();
        } else {
            $videos = Video::select(['id', 'title', 'cover'])->withCount(['visit_logs'])->inRandomOrder()->limit($limit)->get();
        }

        $data = $videos->map(function ($video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                // 'author' => $video->author,
                // 'description' => $video->description,
                'cover' => $video->cover,
                // 'tagged_tags' => $video->tagged_tags,
                'visit_counts' => shortenNumber($video->visit_histories_count),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
