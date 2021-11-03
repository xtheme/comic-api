<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookResource;
use App\Http\Resources\VideoResource;
use App\Models\Channel;
use App\Models\Filter;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class TopicController extends BaseController
{
    protected $repository;

    public function __construct(Request $request, TopicRepositoryInterface $repository)
    {
        if ($request->bearerToken()) {
            $this->middleware('auth:sanctum');
        }

        $this->repository = $repository;
    }

    // 整理不同模型輸出的數據格式
    public function arrangeData($type, $list)
    {
        // 當前用戶是否收藏
        $user = auth('sanctum')->user() ?? null;
        $favorite_logs = [];
        if ($user) {
            $book_ids = $list->pluck('id')->toArray();
            $favorite_logs = $user->favorite_logs()->where('type', $type)->whereIn('item_id', $book_ids)->pluck('item_id')->toArray();
        }

        $list = $list->map(function ($item) use ($type, $favorite_logs) {
            $has_favorite = in_array($item->id, $favorite_logs) ? true : false;

            if ($type == 'book' || $type == 'book_safe') {
                return (new BookResource($item))->favorite($has_favorite);
            } else {
                return (new VideoResource($item))->favorite($has_favorite);
            }
        })->toArray();

        return $list;
    }

    public function list(Request $request, $type)
    {
        if ($request->headers->has('ch')) {
            $safe_landing = Channel::where('id', (int) $request->header('ch'))->where('safe_landing', 1)->exists();
            if ($safe_landing) {
                $type .= '_safe';
            }
        }

        $request->merge([
            'type' => $type,
            'status' => 1,
        ]);

        $topics = $this->repository->filter($request)->get();

        $data = $topics->map(function ($topic) {
            $cache_key = sprintf('topic:%s', $topic->id);

            $list = $topic->filter->buildQuery()->take($topic->limit)->get();

            return Cache::remember($cache_key, 300, function () use ($topic, $list) {
                return [
                    'title' => $topic->filter->title,
                    'filter_id' => $topic->filter_id,
                    'spotlight' => $topic->spotlight,
                    'per_line' => $topic->row,
                    'list' => $this->arrangeData($topic->type, $list),
                ];
            });
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function filter($filter_id, $page = 1)
    {
        $filter = Filter::findOrFail($filter_id);

        $size = 20;

        $query = $filter->buildQuery();

        $count = (clone $query)->count();

        $total_page = ceil($count / $size);

        $list = (clone $query)->forPage($page, $size)->get();

        $list = $this->arrangeData($filter->type, $list);

        $data = [
            'title' => $filter->title,
            'page' => (int) $page,
            'size' => $size,
            'total_page' => (int) $total_page,
            'list' => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
