<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use App\Models\BookChapter;
use App\Models\UserPurchaseLog;
use App\Traits\CacheTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Record;

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
        $book = Book::with(['chapters'])->withCount(['chapters'])->find($id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
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
            'chapters_count' => $book->chapters_count,
            'chapters' => $book->chapters->map(function ($chapter) {
                return [
                    // 'book_id' => $chapter->book_id,
                    'chapter_id' => $chapter->id,
                    'episode' => $chapter->episode,
                    'title' => $chapter->title,
                    'price' => $chapter->price,
                    'created_at' => $chapter->created_at->format('Y-m-d'),
                ];
            })->toArray(),
        ];

        // 訪問數+1
        $book->increment('view_counts');

        // 記錄用戶訪問
        if ($request->user()) {
            Record::from('book')->visit($id);
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
        $chapter = BookChapter::find($chapter_id);

        if (!$chapter) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        // 收費章節
        $protect = true;

        // 如果章節免費
        if ($chapter->price == 0) {
            $protect = false;
        }

        // 是否登入
        if ($request->user()) {
            $user = $request->user();

            $is_purchase = $user->purchase_logs()->where('type', 'book_chapter')->where('item_id', $chapter_id)->exists();

            if ($is_purchase) {
                $protect = false;
            }

            if ($user->is_vip) {
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
            'charge' => $protect,
            'episode' => $chapter->episode,
            'title' => $chapter->title,
            'price' => $chapter->price,
            'content' => $images,
        ];

        // 记录访问
        // Record::from('book')->visit($book_id);

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

        if ($tags) {
            $books =
                Book::select(['id', 'title', 'vertical_cover'])->withCount(['visit_histories'])->withAnyTag($tags)->where('id', '!=', $id)->inRandomOrder()->limit($limit)->get();
        } else {
            $books = Book::select(['id', 'title', 'vertical_cover'])->withCount(['visit_histories'])->inRandomOrder()->limit($limit)->get();
        }

        $data = $books->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                // 'author' => $book->author,
                // 'description' => $book->description,
                'cover' => $book->vertical_cover,
                'tagged_tags' => $book->tagged_tags,
                'visit_counts' => shortenNumber($book->visit_histories_count),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
