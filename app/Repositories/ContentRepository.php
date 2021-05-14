<?php

namespace App\Repositories;

use App\Models\Content;
use App\Notifications\ContentWarning;
use App\Repositories\Contracts\ContentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class ContentRepository extends Repository implements ContentRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return Content::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        // 预设查询30天内的数据, 减少查询时间
        $few_days_ago = Carbon::parse('-30 days')->setTimeFromTimeString('00:00:00')->toDateTimeString();
        $today = Carbon::now()->toDateTimeString();
        $time_period = sprintf('%s - %s', $few_days_ago, $today);

        $keyword = $request->get('keyword') ?? '';
        $vol = $request->get('vol') ?? '';
        $status = $request->get('status') ?? '';
        $model = $request->get('model') ?? '';
        $published_at = $request->get('published_at') ?? '';
        $created_at = $request->get('created_at') ?: '';
        $series = $request->get('series') ?? '';
        $video_encode = $request->get('video_encode') ?? '';
        $tags = $request->get('tags') ?? '';
        $issue = $request->get('issue') ?? '';

        $order = $request->get('order') ?? 'published_at';
        $sort = $request->get('sort') ?? 'desc';

        // 替換 created_at
        if (!$request->has('created_at') && !$request->has('page')) {
            $created_at = $time_period;
            $request->merge([
                'created_at' => $created_at,
            ]);
        }

        return $this->model::withTrashed()->with('model')->withCount(['comments', 'downloads'])
        ->when($keyword, function (Builder $query, $keyword) {
            return $query->where('id', $keyword)
                ->orWhere('title', 'like', '%' . $keyword . '%')
                ->orWhere('author', 'like', '%' . $keyword . '%')
                ->orWhere('video_name', 'like', '%' . $keyword . '%');
        })->when($tags, function (Builder $query, $tags) {
            return $query->withAllTags($tags);
        })->when($vol, function (Builder $query, $vol) {
            return $query->where('vol', 'like', '%' . $vol . '%');
        })->when($status, function (Builder $query, $status) {
            switch ($status) {
                case '2':
                    return $query->where('status', 1)->where('published_at', '<=', date('Y-m-d H:i:s'));
                case '4':
                    return $query->where('status', 1)->where('published_at', '>', date('Y-m-d H:i:s'));
                default:
                    return $query->where('status', '=', (int) $status - 1);
            }
        })->when($model, function (Builder $query, $model) {
            return $query->where('model_id', '=', (int) $model);
        })->when($issue, function (Builder $query) {
            return $query->whereIssue(1);
        })->when($published_at, function (Builder $query, $published_at) {
            $date = explode(' - ', $published_at);
            $start_date = $date[0];
            $end_date = $date[1];

            return $query->whereBetween('published_at', [
                $start_date,
                $end_date,
            ]);
        })->when($created_at, function (Builder $query, $created_at) {
            $date = explode(' - ', $created_at);
            $start_date = $date[0];
            $end_date = $date[1];

            return $query->whereBetween('created_at', [
                $start_date,
                $end_date,
            ]);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order)->orderBy('sort');
            } else {
                return $query->orderBy($order)->orderBy('sort');
            }
        })->when($series, function (Builder $query, $series) {
            $query->whereHas('series', function (Builder $query) use ($series) {
                return $query->where('title', 'like', '%' . $series . '%');
            });
        })->when($video_encode, function (Builder $query, $video_encode) {
            return $query->where('model_id', 3)->where('video_encode', $video_encode - 1);
        });
    }

    /**
     * 一键排查
     *
     * @param int $id
     *
     * @return array
     */
    public function troubleshoot(int $id): array
    {
        $article = Content::find($id);

        // Step.1 要检查的远端文件清单
        $list = $this->getCheckList($article);

        // Step.2 获取检查结果
        $errors = $this->getCheckResult($list);

        if ($errors) {
            // Step.3 检查是否有旧的排查报告
            $notify = $article->unreadNotifications()->count();
            if ($notify) {
                // 删除旧报告
                $article->notifications()->delete();
            }

            // Step.4 建立排查报告
            Notification::send($article, new ContentWarning($errors));

            // Step.5 标示本文章排查后发现异常
            // 不要用 $article->update(['issue' => 1]);, 会重复触发 UpdateArticleQueue
            DB::table('contents')->where('id', $article->id)->update(['issue' => 1]);

        } else {
            $article->notifications()->delete();

            DB::table('contents')->where('id', $article->id)->update(['issue' => 0]);
        }

        return $errors;
    }

    /**
     * 获取要检测的文件
     *
     * @param $article
     *
     * @return array
     */
    public function getCheckList(Content $article): array
    {
        $localhost = request()->getSchemeAndHttpHost();

        // 要检查的远端文件清单
        $list = [];

        // 1. 检查封面
        $list[] = [
            'type' => '封面图片',
            'url'  => getOldConfig('web_config', 'img_domain') . $article->thumb,
        ];

        $list[] = [
            'type' => '本地封面图片',
            'url'  => $localhost . $article->thumb,
        ];

        // 2. 正文图片
        $content = html_entity_decode($article->content);
        preg_match_all( '/src="([^"]*)"/i', $content, $images);
        foreach($images[1] as $key => $image) {
            $list[] = [
                'type' => '正文图片',
                'url'  => getOldConfig('web_config', 'img_domain') . $image,
                'timeout' => 1,
            ];
            if ($key >= 3) break;
        }

        // 3. 影视 (视频)
        if ($article->model_id == 3) {
            $list[] = [
                'type' => '视频封面图片',
                'url'  => getOldConfig('web_config', 'img_domain') . $article->video_cover,
            ];

            $list[] = [
                'type' => '本地视频封面图片',
                'url'  => $localhost . $article->video_cover,
            ];

            $list[] = [
                'type' => '视频网址',
                'url'  => getOldConfig('web_config', 'video_domain') . $article->video,
            ];

            $list[] = [
                'type' => '视频切片',
                'url'  => getOldConfig('web_config', 'hls_domain') . str_replace('.mp4', '/index.m3u8', $article->video),
            ];
        }

        // 3. 电台 (音频)
        if ($article->model_id == 5) {
            $list[] = [
                'type' => '音频网址',
                'url'  => getOldConfig('web_config', 'audio_domain') . $article->video,
            ];

            $list[] = [
                'type' => '音频下载网址',
                'url'  => getOldConfig('web_config', 'audio_download_domain') . $article->video,
            ];
        }

        // Log::info(print_r($list, true));

        return $list;
    }

    /**
     * 获取检测结果
     *
     * @param  array  $list
     *
     * @return array
     */
    public function getCheckResult(array $list = []): array
    {
        $errors = [];

        foreach ($list as $item) {
            $status = $this->isExists($item['url'], $item['timeout'] ?? 2);

            if ($status != 200) {
                $errors[] = [
                    'type'   => $item['type'],
                    'url'    => $item['url'],
                    'status' => $status,
                ];
            }
        }

        return $errors;
    }

    /**
     * 检查远端文件是否存在
     *
     * @param  string  $file
     * @param  int  $timeout
     *
     * @return int
     */
    public function isExists(string $file, int $timeout = 2): int
    {
        try {
            $response = Http::timeout($timeout)->head($file);
            return $response->status();
        } catch (\Exception $e) {
            // 沒關係，是 Time Out 啊！
            return 200;
        }
    }
}
