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

    public function arrangeData($topic)
    {
        switch ($topic->causer) {
            case 'video':
                $list = $topic->query_result->map(function ($item) {
                    return [
                        'id'                    => $item->id,
                        'title'                 => $item->title,
                        'author'                => $item->author,
                        'cover'                 => $item->cover,
                        'tagged_tags'           => $item->tagged_tags,
                        'ribbon'                => $item->ribbon,
                        'visit_counts' => shortenNumber($item->visit_histories_count),
                        'play_counts'  => (request()->header('platform') == 1) ? $item->play_histories_count : shortenNumber($item->play_histories_count),
                    ];
                })->toArray();
                break;

            case 'book':
                $row = $topic->row;
                $list = $topic->query_result->map(function ($item) use ($row) {
                    return [
                        'id'                    => $item->id,
                        'title'                 => $item->title,
                        'author'                => $item->author,
                        'cover'                 => ($row > 2) ? $item->horizontal_cover : $item->vertical_cover,
                        'tagged_tags'           => $item->tagged_tags,
                        'visit_counts' => shortenNumber($item->visit_histories_count),
                    ];
                })->toArray();
                break;
        }

        return $list;
    }

    public function list(Request $request, $causer)
    {
        $request->merge([
            'causer' => $causer,
            'status' => 1,
        ]);

        $topics = $this->repository->filter($request)->get();

        $data = $topics->map(function ($topic) {
            return [
                'topic'     => $topic->id,
                'causer'    => $topic->causer,
                'title'     => $topic->title,
                'tags'      => $topic->properties['tag'] ?? [],
                'spotlight' => $topic->spotlight,
                'per_line'  => $topic->row,
                'list'      => $this->arrangeData($topic),
                // 'list'      => $topic->query_result,
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function more($topic_id, $page = 1)
    {
        $topic = $this->repository->find($topic_id);

        $per_page = 10;

        $count = $topic->setUnlimited()->buildQuery()->count();

        $total_page = ceil($count / $per_page);

        $list = $topic->setUnlimited()->buildQuery()->forPage($page, $per_page)->get();

        switch ($topic->causer) {
            case 'video':
                $list = $list->map(function ($item) {
                    return [
                        'id'                    => $item->id,
                        'title'                 => $item->title,
                        'author'                => $item->author,
                        'cover'                 => $item->cover,
                        'tagged_tags'           => $item->tagged_tags,
                        'ribbon'                => $item->ribbon,
                        'visit_counts' => shortenNumber($item->visit_histories_count),
                        'play_counts'  => (request()->header('platform') == 1) ? $item->play_histories_count : shortenNumber($item->play_histories_count),
                    ];
                })->toArray();
                break;

            case 'book':
                $row = $topic->row;
                $list = $list->map(function ($item) use ($row) {
                    return [
                        'id'                    => $item->id,
                        'title'                 => $item->title,
                        'author'                => $item->author,
                        'cover'                 => ($row > 2) ? $item->horizontal_thumb : $item->vertical_thumb,
                        'tagged_tags'           => $item->tagged_tags,
                        'visit_counts' => shortenNumber($item->visit_histories_count),
                    ];
                })->toArray();
                break;
        }

        $data = [
            'topic'      => $topic_id,
            'title'      => $topic->title,
            'tags'       => $topic->properties['tag'] ?? [],
            'per_page'   => $per_page,
            'total_page' => $total_page,
            'page'       => $page,
            'list'       => $list,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
