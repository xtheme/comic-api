<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $book = Book::with(['chapters', 'chapters.purchase_log'])->withCount(['chapters'])->find($id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $user = $request->user() ?? null;

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
            'chapters_count' => $book->chapters_count,
            'chapters' => $book->chapters->map(function ($chapter) use ($user) {
                if (!$user) {
                    $has_purchased = false;
                } else {
                    if ($user->is_vip) {
                        $has_purchased = true;
                    } else {
                        $has_purchased = $chapter->purchased;
                    }
                }
                return [
                    'chapter_id' => $chapter->id,
                    'episode' => $chapter->episode,
                    'title' => $chapter->title,
                    'price' => $chapter->price,
                    'view_counts' => shortenNumber($chapter->view_counts),
                    'has_purchased' => $has_purchased,
                    'created_at' => $chapter->created_at->format('Y-m-d'),
                ];
            })->toArray(),
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

    /*public function chapters($book_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $data = $book->chapters->map(function ($chapter) {
            return [
                'book_id' => $chapter->book_id,
                'chapter_id' => $chapter->id,
                'episode' => $chapter->episode,
                'title' => $chapter->title,
                'price' => $chapter->price,
                'created_at' => $chapter->created_at->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }*/

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

        // 是否登入
        $user = $request->user() ?? null;

        if ($user) {
            if ($user->is_vip) {
                $protect = false;
            }

            if ($chapter->purchased()->exists()) {
                $protect = false;
            }
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
            'book_id' => $chapter->book_id,
            'protect' => $protect,
            'episode' => $chapter->episode,
            'title' => $chapter->title,
            'price' => $chapter->price,
            'view_counts' => shortenNumber($chapter->view_counts),
            'content' => $images,
            'prev_chapter_id' => $chapter->prev_chapter_id,
            'next_chapter_id' => $chapter->next_chapter_id,
        ];

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function recommend($id = null)
    {
        $limit = 6;
        $tags = [];

        if ($id) {
            $book = Book::findOrFail($id);
            $tags = $book->tagged_tags;
        }

        $books = Book::select(['id', 'title', 'author', 'vertical_cover', 'view_counts'])
                     ->withAnyTag($tags)
                     ->where('id', '!=', $id)
                     ->inRandomOrder()
                     ->limit($limit)
                     ->get();

        $data = $books->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'cover' => $book->vertical_cover,
                'tagged_tags' => $book->tagged_tags,
                'visit_counts' => shortenNumber($book->view_counts),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
