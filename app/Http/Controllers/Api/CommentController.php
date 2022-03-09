<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Services\CommentService;
use App\Services\DunService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{
    private $commentService;
    private $dunService;

    public function __construct(CommentService $commentService, DunService $dunService)
    {
        $this->commentService = $commentService;
        $this->dunService = $dunService;
    }

    public function list(Request $request, $chapter_id, $order)
    {
        $data = Comment::with(['user'])->when($chapter_id, function (Builder $query, $chapter_id) {
            return $query->where('chapter_id', $chapter_id);
        })->where('status', 1)->orderByDesc($order)->get();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function add(Request $request)
    {
        if (!$this->commentService->check_cool_down()) {
            return Response::jsonError('评论过于频繁,请稍候再试！');
        }

        if (!$this->commentService->check_frequency()) {
            return Response::jsonError('超过一天评论次数限制,请珍惜评论次数！');
        }

        if (!$this->dunService->sendRequest($request->post('content'))) {
            return Response::jsonError('评论内容不合法！');
        }

        $request->merge([
            'user_id' => $request->user()->id,
            'status' => 1,
            'likes' => 0,
        ]);

        $comment = Comment::create($request->post());

        if (!$comment) {
            return Response::jsonError('评论失败！');
        }

        $this->commentService->update_cache();

        return Response::jsonSuccess(__('api.success'), []);
    }

    public function like(Request $request, $comment_id)
    {
        if (!$this->commentService->like($comment_id)) {
            return Response::jsonError('您已点赞噢！');
        }

        return Response::jsonSuccess(__('api.success'), []);
    }

    public function comment($page = 1)
    {
        $data = $this->commentService->getMyComments($page);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function destroy(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        if ($comment->user_id != $request->user()->id) {
            return Response::jsonError('无法删除非本人评论！');
        }

        $comment->delete();

        return Response::jsonSuccess(__('api.success'));
    }
}
