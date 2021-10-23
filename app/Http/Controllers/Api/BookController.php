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
        $book = Book::with(['chapters', 'chapters.purchased'])->withCount(['chapters'])->find($id);

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
                    Log::debug('is_vip=' . $user->is_vip);
                    if ($user->is_vip) {
                        $has_purchased = true;
                    } else {
                        $has_purchased = $chapter->purchased()->exists();
                    }
                }
                return [
                    'chapter_id' => $chapter->id,
                    'episode' => $chapter->episode,
                    'title' => $chapter->title,
                    'price' => $chapter->price,
                    'has_purchased' => $has_purchased,
                    'created_at' => $chapter->created_at->format('Y-m-d'),
                ];
            })->toArray(),
        ];

        // 訪問數+1
        // todo 抽離主表, 方便主表緩存, 但不利於查詢排序
        $book->increment('view_counts');

        // 添加到排行榜
        $book->logRanking();

        // 記錄用戶訪問
        if ($request->user()) {
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
        $chapter = BookChapter::with(['purchased'])->find($chapter_id);

        if (!$chapter) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        // 收費章節
        $protect = true;
        $has_purchased = false;

        // 如果章節免費
        if ($chapter->price == 0) {
            $protect = false;
        }

        // 是否登入
        $user = $request->user() ?? null;
        if ($user) {
            if ($user->is_vip) {
                $has_purchased = true;
            } else {
                $has_purchased = $chapter->purchased()->exists();
            }
            $protect = $has_purchased ? false : true;
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
            'has_purchased' => $has_purchased,
            'episode' => $chapter->episode,
            'title' => $chapter->title,
            'price' => $chapter->price,
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
