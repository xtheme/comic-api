<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CommentRepository;
use App\Services\CommentService;
use App\Services\DunService;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{

    private $repository;
    private $commentService;
    private $dunService;

    public function __construct(CommentRepository $repository , CommentService $commentService , DunService $dunService)
    {
        $this->repository = $repository;
        $this->commentService = $commentService;
        $this->dunService = $dunService;
    }


    public function list(Request $request , $chapter_id , $order)
    {

        $data = $this->repository->list($chapter_id , $order)->get();

        return Response::jsonSuccess(__('api.success'), $data);
    }


    public function add(Request $request)
    {
        
        if (!$this->commentService->check_cool_down()){
            return Response::jsonError('评论过于频繁,请稍候再试！');
        }


        if (!$this->commentService->check_frequency()){
            return Response::jsonError('超过一天评论次数限制,请珍惜评论次数！');
        }

        if (!$this->dunService->sendRequest($request->post('content'))){
            return Response::jsonError('评论内容不合法！');
        }


        $request->merge([
            'user_id' => $request->user->id,
            'status'    => 1,
            'likes' => 0
        ]);

        if (!$this->repository->create($request->post())){
            return Response::jsonError('评论失败');
        }

        $this->commentService->update_cache();
        

        return Response::jsonSuccess(__('api.success'), []);
    }


    public function like(Request $request , $comment_id)
    {

        if(!$this->commentService->like($comment_id)){
            return Response::jsonError('您已点赞噢！');
        }

        return Response::jsonSuccess(__('api.success'), []);
    }

    public function comment($page = 1)
    {

        $data = $this->commentService->getMyComments($page);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function destroy(Request $request,$comment_id)
    {

        $data = $this->repository->find_my($request , $comment_id)->exists();

        if(!$data){
            return Response::jsonError('无法删除非本人评论！');
        }

        $this->repository->destroy($comment_id);

        return Response::jsonSuccess(__('api.success'));
    }

    

}
