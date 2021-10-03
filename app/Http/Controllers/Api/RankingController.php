<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ranking;
use App\Traits\CacheTrait;
use Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;

class RankingController extends Controller
{
    const LIMIT = 19;

    use CacheTrait;

    public function day()
    {
        $redis = Redis::connection('readonly');

        $redis_key = 'book:ranking:day:' . date('Y:m:d', strtotime('yesterday'));

        if (!$redis->exists($redis_key)) {
            $redis_key = 'book:ranking:day:' . date('Y:m:d');
        }

        $raw_data = $redis->zrevrange($redis_key, 0, self::LIMIT, ['withscores' => true]);

        $book_ids = collect($raw_data)->keys();

        $books = Book::with(['tags'])->whereIn('id', $book_ids)->get();

        $data = $books->map(function($book) use ($raw_data) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'cover' => $book->vertical_thumb,
                'tags' => $book->tags->pluck('name'),
                'views' => $raw_data[$book->id],
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function week()
    {
        $redis = Redis::connection('readonly');

        $redis_key = 'book:ranking:week:' . date('Y:W', strtotime('last week'));

        if (!$redis->exists($redis_key)) {
            $redis_key = 'book:ranking:week:' . date('Y:W');
        }

        $raw_data = $redis->zrevrange($redis_key, 0, self::LIMIT, ['withscores' => true]);

        $book_ids = collect($raw_data)->keys();

        $books = Book::with(['tags'])->whereIn('id', $book_ids)->get();

        $data = $books->map(function($book) use ($raw_data) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'cover' => $book->vertical_thumb,
                'tags' => $book->tags->pluck('name'),
                'views' => $raw_data[$book->id],
            ];
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function month()
    {
        $redis = Redis::connection('readonly');

        $redis_key = 'ranking:month:' . date('Y-m');

        $cache = $redis->get($redis_key);

        if (!$cache) {
            $rankings = Ranking::with(['book', 'book.tags'])
                               ->selectRaw('book_id, sum(views) as views')
                               ->where('year', date('Y'))
                               ->where('month', date('m'))
                               ->groupBy('book_id')
                               ->orderByDesc('views')
                               ->limit(self::LIMIT)
                               ->get();

            $data = $rankings->map(function ($rank) {
                return [
                    'id' => $rank->book->id,
                    'title' => $rank->book->title,
                    'description' => $rank->book->description,
                    'cover' => $rank->book->vertical_thumb,
                    'tags' => $rank->book->tags->pluck('name'),
                    'views' => $rank->views,
                ];
            })->toArray();

            $redis->set($redis_key, json_encode($data));
            $redis->expire($redis_key, 1440);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function year()
    {
        $redis = Redis::connection('readonly');

        $redis_key = 'ranking:year:' . date('Y');

        $cache = $redis->get($redis_key);

        if (!$cache) {
            $rankings = Ranking::with(['book', 'book.tags'])
                               ->selectRaw('book_id, sum(views) as views')
                               ->where('year', date('Y'))
                               ->groupBy('book_id')
                               ->orderByDesc('views')
                               ->limit(self::LIMIT)
                               ->get();

            $data = $rankings->map(function ($rank) {
                return [
                    'id' => $rank->book->id,
                    'title' => $rank->book->title,
                    'description' => $rank->book->description,
                    'cover' => $rank->book->vertical_thumb,
                    'tags' => $rank->book->tags->pluck('name'),
                    'views' => $rank->views,
                ];
            })->toArray();

            $redis->set($redis_key, json_encode($data));
            $redis->expire($redis_key, 1440);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function japan()
    {
        $cache_key = $this->getCacheKeyPrefix() . 'ranking:japan:' . date('Y-m-d');

        // 一日
        $cache_ttl = 86400;

        $data = Cache::remember($cache_key, $cache_ttl, function () {
            $rankings = Book::with(['latest_chapter'])->where([
                ['status', '1'],
                ['review', '1'],
                ['type', '1'],
            ])->orderByDesc('visits')->limit(100)->get();

            return $rankings->map(function ($ranking, $idx) {
                return [
                    'book_id' => $ranking->id,
                    'ranking' => $idx + 1,
                    'hits' => $ranking->visits,
                    'episode' => $ranking->latest_chapter->episode,
                    'title' => $ranking->title,
                    'description' => $ranking->description,
                    'end' => $ranking->end,
                    'vertical_cover' => $ranking->vertical_cover,
                    'horizontal_cover' => $ranking->horizontal_cover,
                ];
            })->values()->toArray();
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function korea()
    {
        $cache_key = $this->getCacheKeyPrefix() . 'ranking:korea:' . date('Y-m-d');

        // 一日
        $cache_ttl = 86400;

        $data = Cache::remember($cache_key, $cache_ttl, function () {
            $rankings = Book::with(['latest_chapter'])->where([
                ['status', '1'],
                ['review', '1'],
                ['type', '2'],
            ])->orderByDesc('visits')->limit(100)->get();

            return $rankings->map(function ($ranking, $idx) {
                return [
                    'book_id' => $ranking->id,
                    'ranking' => $idx + 1,
                    'hits' => $ranking->visits,
                    'episode' => $ranking->latest_chapter->episode,
                    'title' => $ranking->title,
                    'description' => $ranking->description,
                    'end' => $ranking->end,
                    'vertical_cover' => $ranking->vertical_cover,
                    'horizontal_cover' => $ranking->horizontal_cover,
                ];
            })->values()->toArray();
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function new()
    {
        $cache_key = $this->getCacheKeyPrefix() . 'ranking:new:' . date('Y-m-d');

        // 一日
        $cache_ttl = 86400;

        $data = Cache::remember($cache_key, $cache_ttl, function () {
            $rankings = Book::with(['latest_chapter'])->where([
                ['status', '1'],
                ['review', '1'],
            ])->orderByDesc('created_at')->limit(100)->get();

            return $rankings->map(function ($ranking, $idx) {
                return [
                    'book_id' => $ranking->id,
                    'ranking' => $idx + 1,
                    'hits' => $ranking->visits,
                    'episode' => $ranking->latest_chapter->episode,
                    'title' => $ranking->title,
                    'description' => $ranking->description,
                    'end' => $ranking->end,
                    'vertical_cover' => $ranking->vertical_cover,
                    'horizontal_cover' => $ranking->horizontal_cover,
                ];
            })->values()->toArray();
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

}
