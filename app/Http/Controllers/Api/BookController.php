<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookChapterResource;
use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BookController extends BaseController
{
    public function __construct(Request $request)
    {
        if ($request->bearerToken()) {
            $this->middleware('auth:sanctum');
        }
    }

    public function detail(Request $request, $id)
    {
        $book = Book::with(['chapters', 'favorite_logs'])->withCount(['chapters'])->find($id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $user = $request->user() ?? null;

        $has_favorite = false;
        $purchase_logs = [];

        if ($user) {
            // 收藏紀錄
            $has_favorite = $user->favorite_logs()->where('type', 'book')->where('item_id', $book->id)->exists();

            // 購買記錄
            $chapter_ids = $book->chapters->pluck('id')->toArray();
            if ($user->is_vip) {
                $purchase_logs = $chapter_ids;
            } else {
                $purchase_logs = $user->purchase_logs()->where('type', 'book_chapter')->whereIn('item_id', $chapter_ids)->pluck('item_id')->toArray();
            }
        }

        $data = [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'cover' => $book->vertical_cover,
            'description' => $book->description,
            'end' => $book->end,
            'type' => $book->type,
            'tagged_tags' => $book->tagged_tags,
            'view_counts' => shortenNumber($book->view_counts),
            'collect_counts' => shortenNumber($book->collect_counts),
            'has_favorite' => $has_favorite,
            'chapters_count' => $book->chapters_count,
            // 'chapters' => $chapters,
            'chapters' => $book->chapters->map(function($chapter) use ($purchase_logs) {
                $purchased = in_array($chapter->id, $purchase_logs) ? true : false;
                return (new BookChapterResource($chapter))->purchased($purchased);
            }),
        ];

        // 訪問數+1
        $book->increment('view_counts');

        // 添加到排行榜
        $book->logRanking();

        // 記錄用戶訪問
        if ($user) {
            $request->user()->visit($book);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function chapter(Request $request, $chapter_id)
    {
        $chapter = BookChapter::with(['purchase_log'])->find($chapter_id);

        if (!$chapter) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        // 訪問數+1
        $chapter->increment('view_counts');

        // 收費章節
        $protect = true;

        // 如果章節免費
        if ($chapter->price == 0) {
            $protect = false;
        }

        // 是否購買
        $user = $request->user() ?? null;

        if ($user) {
            if ($user->is_vip) {
                $purchased  = true;
            } else {
                $purchased = $user->purchase_logs()->where('type', 'book_chapter')->where('item_id', $chapter->id)->exists();
            }
        }

        if ($purchased) {
            $protect = false;
        }

        $images = $chapter->content;

        if ($protect) {
            $images = [$images[0]];
        }

        // $images = collect($images)->map(function ($image) {
        //     $file = file_get_contents($image);
        //     $base64 = 'data:image/jpg;base64,' . base64_encode($file);
        //     return [$base64];
        // })->toArray();

        $data = [
            'protect' => $protect,
            'chapter' => (new BookChapterResource($chapter))->purchased($purchased),
            'content' => $images,
            'prev_chapter_id' => $chapter->prev_chapter_id,
            'next_chapter_id' => $chapter->next_chapter_id,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
