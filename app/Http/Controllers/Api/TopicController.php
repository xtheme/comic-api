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
        $list = $list->map(function ($item) use ($type) {
            if ($type == 'book' || $type == 'book_safe') {
                return json_decode((new BookResource($item))->favorite(false)->toJson(), true);
            } else {
                return json_decode((new VideoResource($item))->favorite(false)->toJson(), true);
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

        $cache_key = sprintf('topic:%s', $type);

        $data = Cache::remember($cache_key, 28800, function () use ($request) {
            $topics = $this->repository->filter($request)->get();

            return $topics->map(function ($topic) {
                $list = $topic->filter->buildQuery()->take($topic->limit)->get();

                return [
                    'title' => $topic->filter->title,
                    'filter_id' => $topic->filter_id,
                    'spotlight' => $topic->spotlight,
                    'per_line' => $topic->row,
                    'list' => $this->arrangeData($topic->type, $list),
                ];
            })->toArray();
        });

        // 當前用戶是否收藏
        $user = auth('sanctum')->user() ?? null;

        if ($user) {
            foreach($data as &$topic) {
                $book_ids = collect($topic['list'])->pluck('id')->toArray();
                $favorite_logs = $user->favorite_logs()->where('type', $type)->whereIn('item_id', $book_ids)->pluck('item_id')->toArray();

                foreach($topic['list'] as &$row) {
                    $row['has_favorite'] = in_array($row['id'], $favorite_logs);
                }
            }
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function filter($filter_id, $page = 1)
    {
        $cache_key = sprintf('filter:%s:%s', $filter_id, $page);

        $data = Cache::tags(['filter', $filter_id])->remember($cache_key, 28800, function () use ($filter_id, $page) {
            $filter = Filter::findOrFail($filter_id);

            $size = 20;

            $query = $filter->buildQuery();

            $count = (clone $query)->count();

            $total_page = ceil($count / $size);

            $list = (clone $query)->forPage($page, $size)->get();

            $list = $this->arrangeData($filter->type, $list);

            $data = [
                'title' => $filter->title,
                'type' => $filter->type,
                'page' => (int) $page,
                'size' => $size,
                'total_page' => (int) $total_page,
                'list' => $list,
            ];

            return $data;
        });

        // 當前用戶是否收藏
        $user = auth('sanctum')->user() ?? null;

        if ($user) {
            $book_ids = collect($data['list'])->pluck('id')->toArray();
            $favorite_logs = $user->favorite_logs()->where('type', $data['type'])->whereIn('item_id', $book_ids)->pluck('item_id')->toArray();

            foreach($data['list'] as &$row) {
                $row['has_favorite'] = in_array($row['id'], $favorite_logs);
            }
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
