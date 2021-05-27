<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CommentRepository;
use App\Services\CommentService;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{

    private $repository;
    private $commentService;

    public function __construct(CommentRepository $repository , CommentService $commentService)
    {
        $this->repository = $repository;
        $this->commentService = $commentService;
    }

    public function add(Request $request)
    {
        
        if (!$this->commentService->check_coll_down($request->user->id)){
            return Response::jsonError('评论过于频繁,请稍候再试！');
        }


        if (!$this->commentService->check_frequency($request->user->id)){
            return Response::jsonError('超过一天评论次数限制,请珍惜评论次数！');
        }


        $request->merge([
            'user_id' => $request->user->id,
            'status'    => 1,
            'likes' => 0
        ]);

        if (!$this->repository->create($request->post())){
            return Response::jsonError('评论失败');
        }

        $this->commentService->update_cache($request->user->id);
        

        return Response::jsonSuccess(__('api.success'), []);
    }
}
