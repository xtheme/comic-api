<?php

namespace App\Http\Controllers\Api;

use App\Repositories\BlockRepository;
use App\Repositories\VideoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TopicController extends BaseController
{
    protected $blockRepository;
    protected $videoRepository;

    public function __construct()
    {
        $this->blockRepository = app(BlockRepository::class);
        $this->videoRepository = app(VideoRepository::class);
    }

    public function topic(Request $request, $causer)
    {
        $request->merge([
            'causer' => $causer,
            'status' => 1,
        ]);

        $topics = $this->blockRepository->filter($request)->get();

        $data = $topics->map(function ($topic) {
            return [
                'title'     => $topic->title,
                'spotlight' => $topic->spotlight,
                'per_line'  => $topic->row,
                'list'      => $topic->query_result,
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }


}
