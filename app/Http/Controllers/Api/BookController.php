<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookChapterResource;
use App\Jobs\VisitBook;
use App\Jobs\VisitBookChapter;
use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class BookController extends BaseController
{
    public function __construct(Request $request)
    {
        if ($request->bearerToken()) {
            $this->middleware('auth:sanctum');
        }
    }

    public function detail(Request $request, $book_id)
    {
        $cache_key = 'book:' . $book_id;

        $data = Cache::remember($cache_key, 28800, function () use ($book_id) {
            try {
                $book = Book::with([
                    'chapters' => function ($query) {
                        $query->where('status', 1)->oldest('episode');
                    },
                    'favorite_logs',
                ])->withCount(['chapters'])->findOrFail($book_id);

                return [
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
                    'has_favorite' => false,
                    'chapters_count' => $book->chapters_count,
                    'chapters' => $book->chapters->map(function ($chapter) {
                        return json_decode((new BookChapterResource($chapter))->purchased(false)->toJson(), true);
                    })->toArray(),
                ];
            } catch (\Exception $e) {
                throw new HttpResponseException(Response::jsonError('该漫画不存在或已下架！'));
            }
        });

        $user = $request->user() ?? null;

        if ($user) {
            // 收藏紀錄
            $data['has_favorite'] = $user->favorite_logs()->where('type', 'book')->where('item_id', $book_id)->exists();

            // 購買記錄
            $chapter_ids = collect($data['chapters'])->pluck('chapter_id')->toArray();

            if ($user->is_vip) {
                $purchase_logs = $chapter_ids;
            } else {
                $purchase_logs = $user->purchase_logs()->where('type', 'book_chapter')->whereIn('item_id', $chapter_ids)->pluck('item_id')->toArray();
            }

            foreach ($data['chapters'] as &$chapter) {
                $chapter['purchased'] = in_array($chapter['chapter_id'], $purchase_logs);
            }
        }

        // 排程: 訪問數+1 / 更新漫畫排行榜 / 記錄用戶訪問
        VisitBook::dispatch($book_id, $user);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function chapter(Request $request, $chapter_id)
    {
        $cache_key = 'chapter:' . $chapter_id;

        $data = Cache::remember($cache_key, 28800, function () use ($chapter_id) {
            try {
                $chapter = BookChapter::with(['purchase_log'])->findOrFail($chapter_id);

                return [
                    'protect' => true, // 收費章節
                    'chapter' => json_decode((new BookChapterResource($chapter))->purchased(false)->toJson(), true),
                    'content' => $chapter->content,
                    'prev_chapter_id' => $chapter->prev_chapter_id,
                    'next_chapter_id' => $chapter->next_chapter_id,
                ];
            } catch (\Exception $e) {
                throw new HttpResponseException(Response::jsonError('该漫画不存在或已下架！'));
            }
        });

        // 如果章節免費
        if ($data['chapter']['price'] == 0) {
            $data['protect'] = false;
        }

        // 是否購買
        $user = $request->user() ?? null;

        if ($user) {
            if ($user->is_vip) {
                $purchased = true;
            } else {
                $purchased = $user->purchase_logs()->where('type', 'book_chapter')->where('item_id', $data['chapter']['chapter_id'])->exists();
            }

            $data['chapter']['purchased'] = $purchased;

            if ($purchased) {
                $data['protect'] = false;
            }
        }

        if ($data['protect']) {
            // 如果是收費章節, 只返回第一張圖
            $data['content'] = [$data['content'][0]];
        }

        // 排程: 訪問數+1
        VisitBookChapter::dispatch($chapter_id);

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
