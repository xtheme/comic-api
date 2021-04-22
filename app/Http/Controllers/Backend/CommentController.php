<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Config;
use App\Repositories\CommentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{
    private $repository;

    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = $this->repository->filter($request)->paginate();

        return view('backend.comment.index', [
            'list' => $list,
            'pageConfigs' => ['hasSearchForm' => true],
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return Response::jsonSuccess('删除成功！');

    }

    public function batchDestroy(Request $request)
    {
        Comment::destroy($request->post('ids'));
        return Response::jsonSuccess('删除成功！');
    }


}
