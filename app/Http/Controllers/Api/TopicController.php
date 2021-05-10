<?php

namespace App\Http\Controllers\Api;

use App\Repositories\BlockRepository;
use App\Repositories\VideoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TopicController extends BaseController
{
    protected $blockRepository;

    // protected $videoRepository;

    public function __construct()
    {
        $this->blockRepository = app(BlockRepository::class);
        // $this->videoRepository = app(VideoRepository::class);
    }

    public function list(Request $request, $causer)
    {
        $request->merge([
            'causer' => $causer,
            'status' => 1,
        ]);

        $topics = $this->blockRepository->filter($request)->get();

        $data = $topics->map(function ($topic) {
            return [
                'topic'     => $topic->id,
                'title'     => $topic->title,
                'tags'      => $topic->properties['tag'] ?? [],
                'spotlight' => $topic->spotlight,
                'per_line'  => $topic->row,
                'list'      => $topic->query_result,
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function more($topic_id, $page = 1)
    {
        $topic = $this->blockRepository->find($topic_id);

        $per_page = 10;

        $count = $topic->setUnlimited()->buildQuery()->count();

        $total_page = ceil($count / $per_page);

        $list = $topic->setUnlimited()->buildQuery()->forPage($page, $per_page)->get();

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
