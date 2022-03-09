<?php

namespace App\Http\Controllers\Api;

use App\Jobs\VisitVideo;
use App\Models\Video;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $cache_key = 'video:detail:' . $video_id;

        $data = Cache::remember($cache_key, 28800, function () use ($video_id) {
            try {
                $video = Video::withCount(['visit_logs', 'favorite_logs'])->findOrFail($video_id);

                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'cover' => $video->cover,
                    'description' => $video->description,
                    'length' => $video->length,
                    'number' => $video->number, // 番号
                    'actor' => $video->actor,
                    'keywords' => $video->keywords,
                    'view_counts' => shortenNumber($video->view_counts),
                    'collect_counts' => shortenNumber($video->collect_counts),
                    'has_favorite' => false,
                    'purchased' => false,
                ];
            } catch (\Exception $e) {
                throw new HttpResponseException(Response::jsonError('视频不存在或已下架！'));
            }
        });

        $user = $request->user() ?? null;

        if ($user) {
            // 收藏紀錄
            $data['has_favorite'] = $user->favorite_logs()->where('type', 'book')->where('item_id', $video_id)->exists();

            // 購買記錄
            if ($user->is_vip) {
                $data['purchased'] = true;
            } else {
                $data['purchased'] = $user->purchase_logs()->where('type', 'video')->where('item_id', $video_id)->exists();
            }
        }

        // 排程: 訪問數+1 / 更新排行榜 / 記錄用戶訪問
        VisitVideo::dispatch($video_id, $user);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    // 點擊播放, 驗證權限, 返回 HLS 網址
    public function play(Request $request, $video_id = null)
    {
        $user = $request->user() ?? null;

        if (!$user) {
            return Response::jsonError('请先登录会员！');
        }

        // 購買記錄
        if ($user->is_vip) {
            $purchased = true;
        } else {
            $purchased = $user->purchase_logs()->where('type', 'video')->where('item_id', $video_id)->exists();
        }

        // 針對免費仔
        if (!$purchased) {
            $cache_key = 'free:video:' . $user->id;

            if (Cache::has($cache_key)) {
                $free_count = Cache::get($cache_key);
                // 每日免费观看次数
                if ($free_count < getConfig('video', 'daily_free_views', 3)) {
                    $purchased = true;
                    Cache::increment($cache_key);
                }
            } else {
                $purchased = true;
                $ttl = getTtlRemainingToday();
                Cache::put($cache_key, 1, $ttl);
            }
        }

        if (!$purchased) {
            return Response::jsonError('您今天的免费次数已用尽！');
        } else {
            try {
                $video = Video::select(['storage_path'])->findOrFail($video_id);

                $data = [
                    'storage_path' => $video->storage_path,
                ];

                return Response::jsonSuccess(__('api.success'), $data);
            } catch (\Exception $e) {
                throw new HttpResponseException(Response::jsonError('视频不存在或已下架！'));
            }
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
            $videos = Video::withCount(['visit_logs'])->withAnyTags($tags)->where('id', '!=', $id)->inRandomOrder()->limit($limit)->get();
        } else {
            $videos = Video::withCount(['visit_logs'])->inRandomOrder()->limit($limit)->get();
        }

        $data = $videos->map(function ($video) {
            return [
                'id' => $video->id,
                'title' => $video->title,
                'cover' => $video->cover,
                'description' => $video->description,
                'number' => $video->number, // 番号
                'actor' => $video->actor,
                'keywords' => $video->keywords,
                'view_counts' => $video->view_counts,
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
