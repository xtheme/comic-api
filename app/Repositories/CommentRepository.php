<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommentRepository extends Repository implements CommentRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return Comment::class;
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {

        $username = $request->get('username') ?? '';
        $book_title = $request->get('book_title') ?? '';
        $chapter_title = $request->get('chapter_title') ?? '';
        $status = $request->get('status') ?? '';
        $date_register = $request->get('date_register') ?? '';
        $order = $request->get('order') ?? 'created_at';

        return $this->model::with(['user' , 'book_chapter' , 'book_chapter.book'])
            ->when($username, function (Builder $query, $username) {
                $query->whereHas('user', function (Builder $query) use ($username) {
                    return $query->where('username', 'like', '%' . $username . '%');
                });
            })->when($book_title, function (Builder $query, $book_title) {
                $query->whereHas('book_chapter.book', function (Builder $query) use ($book_title) {
                    return $query->where('id', '=', $book_title)->orWhere('title' , 'like' , '%' . $book_title . '%');
                });
            })->when($chapter_title, function (Builder $query, $chapter_title) {
                $query->whereHas('book_chapter', function (Builder $query) use ($chapter_title) {
                    return $query->where('title' , 'like' , '%' . $chapter_title . '%');
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


        /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function list($chapter_id , $order): Builder
    {
        return $this->model::with(['user'])
            ->when($chapter_id, function (Builder $query, $chapter_id) {
                return $query->where('chapter_id', $chapter_id);
            })->where('status', 1)->orderByDesc($order);
    }

    /**
     * @param  Request  $request
     *
     * @return Builder
     */
    public function find_my($request , $comment_id): Builder
    {
        return $this->model::where([
            ['user_id' , $request->user()->id],
            ['id' , $comment_id]
        ]);
    }
    
}
