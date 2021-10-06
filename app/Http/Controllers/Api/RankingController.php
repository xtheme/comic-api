<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ranking;
use App\Traits\CacheTrait;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;

class RankingController extends Controller
{
    const LIMIT = 19;
    const CACHE_TTL = 3600;

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

        $redis_key = 'book:ranking:month:' . date('Y-m');

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

            $redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function year()
    {
        $redis = Redis::connection('readonly');

        $redis_key = 'book:ranking:year:' . date('Y');

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

            $redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    private function typeRanking($type)
    {
        $redis = Redis::connection('readonly');

        $redis_key = 'book:ranking:' . $type;

        $cache = $redis->get($redis_key);

        if (!$cache) {
            $books = Book::where(function ($query) use ($type) {
                $type = ($type == 'japan') ? 1 : 2;
                $query->where('status', 1)->where('type', $type);
            })->orderByDesc('view_counts')->limit(self::LIMIT)->get();

            $data = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'cover' => $book->vertical_thumb,
                    'tags' => $book->tags->pluck('name'),
                    'views' => $book->view_counts,
                    'updated_at' => $book->updated_at->format('Y-m-d'),
                ];
            })->toArray();

            $redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function japan()
    {
        return $this->typeRanking('japan');
    }

    public function korea()
    {
        return $this->typeRanking('korea');
    }

    public function latest()
    {
        $redis = Redis::connection('readonly');

        $redis_key = 'book:ranking:latest';

        $cache = $redis->get($redis_key);

        if (!$cache) {
            $books = Book::where('status', 1)->latest('updated_at')->limit(self::LIMIT)->get();

            $data = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'cover' => $book->vertical_thumb,
                    'tags' => $book->tags->pluck('name'),
                    'views' => $book->view_counts,
                    'updated_at' => $book->updated_at->format('Y-m-d'),
                ];
            })->toArray();

            $redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

}
