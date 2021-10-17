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
    use CacheTrait;

    public function detail($id)
    {
        // $book = Book::with(['chapters'])->withCount(['chapters', 'visit_histories', 'favorite_histories'])->find($id);
        $book = Book::with(['chapters'])->withCount(['chapters', 'visit_histories'])->find($id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $data = [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'cover' => $book->horizontal_cover,
            'description' => $book->description,
            // 'charge' => $book->charge,
            'end' => $book->end,
            'type' => $book->type,
            'release_at' => $book->release_at,
            'latest_chapter_title' => $book->chapters_count ? $book->chapters->first()->title : '',
            'tagged_tags' => $book->tagged_tags,
            'visit_counts' => shortenNumber($book->visit_histories_count),
            'favorite_counts' => shortenNumber($book->favorite_histories_count),
            'chapters' => $book->chapters->map(function ($chapter) {
                return [
                    'book_id' => $chapter->book_id,
                    'chapter_id' => $chapter->id,
                    'title' => $chapter->title,
                    'price' => $chapter->price,
                    'created_at' => $chapter->created_at->format('Y-m-d'),
                ];
            })->toArray(),
        ];

        // 訪問數+1
        Record::from('book')->visit($id);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function chapters($book_id)
    {
        $book = Book::find($book_id);

        if (!$book) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        $data = $book->chapters->map(function ($chapter) {
            return [
                'book_id' => $chapter->book_id,
                'chapter_id' => $chapter->id,
                'title' => $chapter->title,
                'price' => $chapter->price,
                'created_at' => $chapter->created_at->format('Y-m-d'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function chapter(Request $request, $book_id, $chapter_id)
    {
        $chapter = BookChapter::where('book_id', $book_id)->find($chapter_id);

        if (!$chapter) {
            return Response::jsonError('该漫画不存在或已下架！');
        }

        // 收費章節
        $protect = true;

        if ($chapter->price == 0) {
            $protect = false;
        }

        // 是否登入
        $user = auth('sanctum')->user() ?? null;

        if ($user) {
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
