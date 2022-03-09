<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\RankingLog;
use App\Models\Video;
use Illuminate\Redis\Connections\Connection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;

class RankingController extends Controller
{
    const LIMIT = 19;
    const CACHE_TTL = 3600;

    public Connection $redis;

    public function __construct()
    {
        // 不過期的數據庫
        $this->redis = Redis::connection('readonly');
    }

    public function day($type = 'book')
    {
        // 優先給昨天數據
        $redis_key = $type . ':ranking:day:' . date('Y:m:d', strtotime('yesterday'));

        if (!$this->redis->exists($redis_key)) {
            $redis_key = $type . ':ranking:day:' . date('Y:m:d');
        }
        // dd($redis_key);
        $raw_data = $this->redis->zrevrange($redis_key, 0, self::LIMIT, ['withscores' => true]);

        $ids = collect($raw_data)->keys();

        $data = $this->redisTransformation($type, $ids, $raw_data);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    private function redisTransformation($type, $ids, $raw_data)
    {
        switch ($type) {
            case 'video':
                $videos = Video::whereIn('id', $ids)->get();

                $data = $videos->map(function ($video) use ($raw_data) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'cover' => $video->cover,
                        'description' => $video->description,
                        'tagged_tags' => $video->keywords,
                        'views' => $raw_data[$video->id],
                    ];
                });
                break;
            case 'book':
            default:
                $books = Book::with(['tags'])->whereIn('id', $ids)->get();

                $data = $books->map(function ($book) use ($raw_data) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'cover' => $book->vertical_cover,
                        'description' => $book->description,
                        'tagged_tags' => $book->tagged_tags,
                        'views' => $raw_data[$book->id],
                    ];
                });
                break;
        }

        return $data;
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
                ->where('type', $type)
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

    private function dataTransformation($type, $rankings)
    {
        switch ($type) {
            case 'video':
                $data = $rankings->map(function ($rank) {
                    return [
                        'id' => $rank->video->id,
                        'title' => $rank->video->title,
                        'description' => $rank->video->description,
                        'cover' => $rank->video->cover,
                        'keywords' => $rank->video->keywords,
                        'views' => $rank->views,
                    ];
                })->toArray();
                break;
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

    public function year($type = 'book')
    {
        $redis_key = $type . ':ranking:year:' . date('Y');

        $cache = $this->redis->get($redis_key);

        if (!$cache) {
            $rankings = RankingLog::with([$type])
                ->selectRaw('item_id, sum(views) as views')
                ->where('type', $type)
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

    public function japan($type = 'book')
    {
        return $this->filterCountry($type, 'japan');
    }

    private function filterCountry($type, $country)
    {
        $redis_key = $type . ':ranking:' . $country;

        $cache = $this->redis->get($redis_key);

        if ($cache) {
            $data = json_decode($cache, true);
            return Response::jsonSuccess(__('api.success'), $data);
        }

        $result = Book::where(function ($query) use ($country) {
            $country = ($country == 'japan') ? 1 : 2;
            $query->where('status', 1)->where('type', $country);
        })->orderByDesc('view_counts')->limit(self::LIMIT)->get();


        $data = $this->sortData($type, $result);

        return Response::jsonSuccess(__('api.success'), $data);
    }

    private function sortData($type, $data, $redis_key)
    {
        switch ($type) {
            case 'video':
                $list = $data->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'description' => $video->description,
                        'cover' => $video->cover,
                        'tagged_tags' => $video->keywords,
                        'views' => $video->view_counts,
                        'created_at' => $video->created_at->format('Y-m-d'),
                    ];
                })->toArray();
                break;
            case 'book':
            default:
                $list = $data->map(function ($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->title,
                        'description' => $book->description,
                        'cover' => $book->vertical_cover,
                        'tagged_tags' => $book->tagged_tags,
                        'views' => $book->view_counts,
                        'created_at' => $book->created_at->format('Y-m-d'),
                    ];
                })->toArray();
                break;
        }

        $this->redis->set($redis_key, json_encode($list, JSON_UNESCAPED_UNICODE));
        $this->redis->expire($redis_key, self::CACHE_TTL);

        return $list;
    }

    public function korea($type = 'book')
    {
        return $this->filterCountry($type, 'korea');
    }

    public function latest($type = 'book')
    {
        $redis_key = $type . ':ranking:latest';

        $cache = $this->redis->get($redis_key);

        if ($cache) {
            $data = json_decode($cache, true);
            return Response::jsonSuccess(__('api.success'), $data);
        }

        switch ($type) {
            case 'video':
                $result = Video::where('status', 1)->latest('created_at')->limit(self::LIMIT)->get();
                break;
            case 'book':
            default:
                $result = Book::where('status', 1)->latest('created_at')->limit(self::LIMIT)->get();
                break;
        }

        $data = $this->sortData($type, $result, $redis_key);

        return Response::jsonSuccess(__('api.success'), $data);
    }
}
