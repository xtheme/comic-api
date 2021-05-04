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

    public function video(Request $request)
    {
        $request->merge([
            'causer' => 'video',
        ]);

        $data = $this->blockRepository->filter($request)->take(1)->get();

        return $data;
        // return Response::jsonSuccess(__('api.success'), $data);
    }


}
