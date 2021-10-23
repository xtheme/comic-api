<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\TopicRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TopicController extends BaseController
{
    protected $repository;

    public function __construct(TopicRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    // 整理不同模型輸出的數據格式
    public function arrangeData($type, $list, $row = 3)
    {
        switch ($type) {
            case 'video':
                $list = $list->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'author' => $item->author,
                        'cover' => $item->cover,
                        'tagged_tags' => $item->tagged_tags,
                        // 'ribbon' => $item->ribbon,
                        // 'visit_counts' => shortenNumber($item->visit_histories_count),
                        // 'play_counts' => (request()->header('platform') == 1) ? $item->play_histories_count : shortenNumber($item->play_histories_count),
                    ];
                })->toArray();
                break;

            case 'book':
                $list = $list->map(function ($item) use ($row) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'author' => $item->author,
                        'cover' => ($row > 2) ? $item->vertical_cover : $item->horizontal_cover,
                        'tagged_tags' => $item->tagged_tags,
                        // 'visit_counts' => shortenNumber($item->visit_histories_count),
                    ];
                })->toArray();
                break;
        }

        return $list;
    }

    public function list(Request $request, $type)
    {
        $request->merge([
            'type' => $type,
            'status' => 1,
        ]);

        $topics = $this->repository->filter($request)->get();

        $data = $topics->map(function ($topic) {

            $list = $topic->filter->buildQuery()->take($topic->limit)->get();

            return [
                'topic' => $topic->id,
                'type' => $topic->type,
                'title' => $topic->title,
                'spotlight' => $topic->spotlight,
                'per_line' => $topic->row,
                'list' => $this->arrangeData($topic->type, $list, $topic->row),
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function more($topic_id, $page = 1)
    {
        $topic = $this->repository->find($topic_id);

        $per_page = 20;

        $count = $topic->filter->buildQuery()->count();

        $total_page = ceil($count / $per_page);

        $list = $topic->filter->buildQuery()->forPage($page, $per_page)->get();

        $list = $this->arrangeData($topic->type, $list);

        $data = [
            'topic' => $topic_id,
            'title' => $topic->title,
            'per_page' => $per_page,
            'total_page' => $total_page,
            'page' => $page,
            'list' => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
