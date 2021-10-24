<?php

namespace App\Http\Controllers\Api;

use App\Models\Filter;
use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class TopicController extends BaseController
{
    protected $repository;

    public function __construct(TopicRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // 整理不同模型輸出的數據格式
    public function arrangeData($type, $list)
    {
        $list = $list->map(function ($item) use ($type) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'author' => $item->author,
                'cover' => ($type == 'book') ? $item->horizontal_cover : $item->cover,
                'tagged_tags' => $item->tagged_tags,
                'view_counts' => shortenNumber($item->view_counts),
                'created_at' => optional($item->created_at)->format('Y-m-d'),
            ];
        })->toArray();

        return $list;
    }

    public function list(Request $request, $type)
    {
        $request->merge([
            'type' => $type,
            'status' => 1,
        ]);

        $cache_key = sprintf('topic:%s', $type);

        if (Cache::has($cache_key)) {
            $data = Cache::get($cache_key);
        } else {
            $topics = $this->repository->filter($request)->get();

            $data = $topics->map(function ($topic) {
                $list = $topic->filter->buildQuery()->take($topic->limit)->get();

                return [
                    'title' => $topic->filter->title,
                    'filter_id' => $topic->filter_id,
                    'spotlight' => $topic->spotlight,
                    'per_line' => $topic->row,
                    'list' => $this->arrangeData($topic->type, $list),
                ];
            });

            Cache::set($cache_key, $data, 3600);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function filter($filter_id, $page = 1)
    {
        $filter = Filter::findOrFail($filter_id);

        $size = 20;

        $count = $filter->buildQuery()->count();

        $total_page = ceil($count / $size);

        $list = $filter->buildQuery()->forPage($page, $size)->get();

        $list = $this->arrangeData($filter->type, $list);

        $data = [
            'title' => $filter->title,
            'page' => $page,
            'size' => $size,
            'total_page' => $total_page,
            'list' => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
