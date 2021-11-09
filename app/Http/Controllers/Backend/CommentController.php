<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{
    private function filter(Request $request): Builder
    {
        $username = $request->get('username') ?? '';
        $book_title = $request->get('book_title') ?? '';
        $chapter_title = $request->get('chapter_title') ?? '';
        $status = $request->get('status') ?? '';
        $date_register = $request->get('date_register') ?? '';
        $order = $request->get('order') ?? 'created_at';

        return Comment::with(['user', 'book_chapter', 'book_chapter.book'])->when($username, function (Builder $query, $username) {
                $query->whereHas('user', function (Builder $query) use ($username) {
                    return $query->where('username', 'like', '%' . $username . '%');
                });
            })->when($book_title, function (Builder $query, $book_title) {
                $query->whereHas('book_chapter.book', function (Builder $query) use ($book_title) {
                    return $query->where('id', '=', $book_title)->orWhere('title', 'like', '%' . $book_title . '%');
                });
            })->when($chapter_title, function (Builder $query, $chapter_title) {
                $query->whereHas('book_chapter', function (Builder $query) use ($chapter_title) {
                    return $query->where('title', 'like', '%' . $chapter_title . '%');
                });
            })->when($status, function (Builder $query, $status) {
                return $query->where('status', $status);
            })->when($date_register, function (Builder $query, $date_register) {
                $date = explode(' - ', $date_register);
                $start_date = $date[0] . ' 00:00:00';
                $end_date = $date[1] . ' 23:59:59';

                return $query->whereBetween('created_at', [
                    $start_date,
                    $end_date,
                ]);
            })->orderByDesc($order);
    }

    public function index(Request $request)
    {
        $list = $this->filter($request)->paginate();

        return view('backend.comment.index', [
            'list' => $list,
            'pageConfigs' => ['hasSearchForm' => true],
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return Response::jsonSuccess(__('response.destroy.success'));
    }

    public function batchDestroy(Request $request)
    {
        Comment::destroy($request->post('ids'));

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
