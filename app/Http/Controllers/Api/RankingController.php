<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\RankingLog;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;

class RankingController extends Controller
{
    const LIMIT = 19;
    const CACHE_TTL = 3600;

    public $redis;

    public function __construct()
    {
        // 不過期的數據庫
        $this->redis = Redis::connection('readonly');
    }

    private function redisTransformation($type, $ids, $raw_data)
    {
        switch ($type) {
            case 'book':
            default:
                $books = Book::with(['tags'])->whereIn('id', $ids)->get();

                $data = $books->map(function($book) use ($raw_data) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'description' => $book->description,
                        'cover' => $book->vertical_cover,
                        'tagged_tags' => $book->tagged_tags,
                        'views' => $raw_data[$book->id],
                    ];
                });
                break;
        }

        return $data;
    }

    private function dataTransformation($type, $rankings)
    {
        switch ($type) {
            case 'book':
            default:
                $data = $rankings->map(function ($rank) {
                    return [
                        'id' => $rank->book->id,
                        'title' => $rank->book->title,
                        'description' => $rank->book->description,
                        'cover' => $rank->book->vertical_cover,
                        'tagged_tags' => $rank->book->tagged_tags,
                        'views' => $rank->views,
                    ];
                })->toArray();
                break;
        }

        return $data;
    }

    public function day($type = 'book')
    {
        // 優先給昨天數據
        $redis_key = $type . ':ranking:day:' . date('Y:m-d', strtotime('yesterday'));

        if (!$this->redis->exists($redis_key)) {
            $redis_key = $type . ':ranking:day:' . date('Y:m-d');
        }

        $raw_data = $this->redis->zrevrange($redis_key, 0, self::LIMIT, ['withscores' => true]);

        $ids = collect($raw_data)->keys();

        $data = $this->redisTransformation($type, $ids, $raw_data);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function week($type = 'book')
    {
        // 優先給上週數據
        $redis_key = $type . ':ranking:week:' . date('Y:W', strtotime('last week'));

        if (!$this->redis->exists($redis_key)) {
            $redis_key = $type . ':ranking:week:' . date('Y:W');
        }

        $raw_data = $this->redis->zrevrange($redis_key, 0, self::LIMIT, ['withscores' => true]);

        $ids = collect($raw_data)->keys();

        $data = $this->redisTransformation($type, $ids, $raw_data);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function month($type = 'book')
    {
        $redis_key = $type . ':ranking:month:' . date('Y-m');

        $cache = $this->redis->get($redis_key);

        if (!$cache) {
            $rankings = RankingLog::with([$type])
                                  ->selectRaw('item_id, sum(views) as views')
                                  ->where('year', date('Y'))
                                  ->where('month', date('m'))
                                  ->groupBy('item_id')
                                  ->orderByDesc('views')
                                  ->limit(self::LIMIT)
                                  ->get();

            $data = $this->dataTransformation($type, $rankings);

            $this->redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function year($type = 'book')
    {
        $redis_key = $type . ':ranking:year:' . date('Y');

        $cache = $this->redis->get($redis_key);

        if (!$cache) {
            $rankings = RankingLog::with([$type])
                                  ->selectRaw('item_id, sum(views) as views')
                                  ->where('year', date('Y'))
                                  ->groupBy('item_id')
                                  ->orderByDesc('views')
                                  ->limit(self::LIMIT)
                                  ->get();

            $data = $this->dataTransformation($type, $rankings);

            $this->redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    private function filterCountry($type, $country)
    {
        $redis_key = $type . ':ranking:' . $country;

        $cache = $this->redis->get($redis_key);

        if (!$cache) {
            $books = Book::where(function ($query) use ($country) {
                $country = ($country == 'japan') ? 1 : 2;
                $query->where('status', 1)->where('type', $country);
            })->orderByDesc('view_counts')->limit(self::LIMIT)->get();

            $data = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'cover' => $book->vertical_cover,
                    'tagged_tags' => $book->tagged_tags,
                    'views' => $book->view_counts,
                    'updated_at' => $book->updated_at->format('Y-m-d'),
                ];
            })->toArray();

            $this->redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function japan($type = 'book')
    {
        return $this->filterCountry($type, 'japan');
    }

    public function korea($type = 'book')
    {
        return $this->filterCountry($type, 'korea');
    }

    public function latest($type = 'book')
    {
        $redis_key = $type . ':ranking:latest';

        $cache = $this->redis->get($redis_key);

        if (!$cache) {
            $books = Book::where('status', 1)->latest('updated_at')->limit(self::LIMIT)->get();

            $data = $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'description' => $book->description,
                    'cover' => $book->vertical_cover,
                    'tagged_tags' => $book->tagged_tags,
                    'views' => $book->view_counts,
                    'updated_at' => $book->updated_at->format('Y-m-d'),
                ];
            })->toArray();

            $this->redis->set($redis_key, json_encode($data, JSON_UNESCAPED_UNICODE));
            $this->redis->expire($redis_key, self::CACHE_TTL);
        } else {
            $data = json_decode($cache, true);
        }

        return Response::jsonSuccess(__('api.success'), $data);
    }

}
