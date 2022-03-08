<?php

namespace App\Http\Controllers\Api;

use App\Jobs\VisitVideo;
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

    public function detail(Request $request, $video_id)
    {
        try {
            $data = Video::withCount(['visit_logs', 'favorite_logs'])->findOrFail($video_id);

            // todo 數字格式化
            $data['visit_counts'] = shortenNumber($data['visit_logs_count']);
            $data['favorite_counts'] = shortenNumber($data['favorite_logs_count']);

            $user = $request->user() ?? null;

            if ($user) {
                // 收藏紀錄
                $data['has_favorite'] = $user->favorite_logs()->where('type', 'book')->where('item_id', $video_id)->exists();

                // 購買記錄
                // $chapter_ids = collect($data['chapters'])->pluck('chapter_id')->toArray();
                //
                // if ($user->is_vip) {
                //     $purchase_logs = $chapter_ids;
                // } else {
                //     $purchase_logs = $user->purchase_logs()->where('type', 'video')->whereIn('item_id', $chapter_ids)->pluck('item_id')->toArray();
                // }
                //
                // foreach ($data['chapters'] as &$chapter) {
                //     $chapter['purchased'] = in_array($chapter['chapter_id'], $purchase_logs);
                // }
            }

            // 排程: 訪問數+1 / 更新漫畫排行榜 / 記錄用戶訪問
            VisitVideo::dispatch($video_id, $user);

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
