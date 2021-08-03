<?php

namespace App\Http\Controllers\Api;

use Cache;
use App\Models\Book;
use App\Models\Rankings;
use App\Traits\CacheTrait;
use App\Models\ViewsRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\RankingService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;

class RankingController extends Controller
{

    use CacheTrait;

    public function day()
    {

        $cache_key = $this->getCacheKeyPrefix() . 'ranking:day:' . Carbon::yesterday()->toDateString();

        //一日
        $cache_ttl = 86400;

        $data = Cache::remember($cache_key, $cache_ttl, function () {

            $rankings = Rankings::with(['book' , 'book.latest_chapter'])
                //->whereDate('date', '=', '2021-07-20')
                ->whereDate('date', '=', Carbon::yesterday()->toDateString())
                ->orderByDesc('hits')
                ->limit(100)
                ->get();

            return $rankings->reject(function($ranking) {
                    return (!$ranking->book);
                })->map(function ($ranking , $idx) {
                    return [
                        'book_id' => $ranking->book->id,
                        'ranking' => $idx+1,
                        'hits' => $ranking->hits,
                        'episode' => $ranking->book->latest_chapter->episode,
                        'title' => $ranking->book->title,
                        'description' => $ranking->book->description,
                        'end' => $ranking->book->end,
                        'vertical_cover' => $ranking->book->vertical_cover,
                        'horizontal_cover' => $ranking->book->horizontal_cover
                    ];
            })->values()->toArray();
        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function week()
    {
        $week = Carbon::now()->subDays(7)->startOfWeek()->weekOfYear;

        $cache_key = $this->getCacheKeyPrefix() . 'ranking:week:' . $week;

        //一周
        $cache_ttl = 604800;

        $data = Cache::remember($cache_key, $cache_ttl, function () {

            $rankings = ViewsRanking::with(['book' , 'book.latest_chapter'])
                        ->whereRaw('year = YEAR(CURRENT_TIME)')
                        ->whereRaw('week = WEEK(CURRENT_TIME)-1')
                        //->whereRaw('week = WEEK(CURRENT_TIME)-2')
                        ->orderByDesc('hits')
                        ->limit(100)
                        ->get();

            return $rankings->reject(function($ranking) {
                return (!$ranking->book);
            })->map(function ($ranking , $idx) {
                return [
                    'book_id' => $ranking->book->id,
                    'ranking' => $idx+1,
                    'hits' => $ranking->hits,
                    'episode' => $ranking->book->latest_chapter->episode,
                    'title' => $ranking->book->title,
                    'description' => $ranking->book->description,
                    'end' => $ranking->book->end,
                    'vertical_cover' => $ranking->book->vertical_cover,
                    'horizontal_cover' => $ranking->book->horizontal_cover
                ];
            })->values()->toArray();

        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function moon()
    {
        
        $date = Carbon::now()->startOfMonth()->subMonth()->rawFormat('Y-m');

        $cache_key = $this->getCacheKeyPrefix() . 'ranking:moon:' . $date;

        $data = Cache::rememberForever($cache_key, function () {

            $rankings = ViewsRanking::with(['book' , 'book.latest_chapter'])
                        ->selectRaw('book_id , sum(hits) as hits')
                        ->whereRaw('year = YEAR(CURRENT_TIME)')
                        ->whereRaw('month = MONTH(CURRENT_TIME)-1')
                        ->groupBy('book_id')
                        ->orderByDesc('hits')
                        ->limit(100)
                        ->get();

            return $rankings->reject(function($ranking) {
                return (!$ranking->book);
            })->map(function ($ranking , $idx) {
                return [
                    'book_id' => $ranking->book->id,
                    'ranking' => $idx+1,
                    'hits' => $ranking->hits,
                    'episode' => $ranking->book->latest_chapter->episode,
                    'title' => $ranking->book->title,
                    'description' => $ranking->book->description,
                    'end' => $ranking->book->end,
                    'vertical_cover' => $ranking->book->vertical_cover,
                    'horizontal_cover' => $ranking->book->horizontal_cover
                ];
            })->values()->toArray();

        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function year()
    {
        
        $date = Carbon::now()->rawFormat('Y');

        $cache_key = $this->getCacheKeyPrefix() . 'ranking:year:' . $date;

        //一周
        $cache_ttl = 604800;

        $data = Cache::remember($cache_key, $cache_ttl, function () {

            $rankings = ViewsRanking::with(['book' , 'book.latest_chapter'])
                        ->selectRaw('book_id , sum(hits) as hits')
                        ->whereRaw('year = YEAR(CURRENT_TIME)')
                        ->groupBy('book_id')
                        ->orderByDesc('hits')
                        ->limit(100)
                        ->get();

            return $rankings->reject(function($ranking) {
                return (!$ranking->book);
            })->map(function ($ranking , $idx) {
                return [
                    'book_id' => $ranking->book->id,
                    'ranking' => $idx+1,
                    'hits' => $ranking->hits,
                    'episode' => $ranking->book->latest_chapter->episode,
                    'title' => $ranking->book->title,
                    'description' => $ranking->book->description,
                    'end' => $ranking->book->end,
                    'vertical_cover' => $ranking->book->vertical_cover,
                    'horizontal_cover' => $ranking->book->horizontal_cover
                ];
            })->values()->toArray();

        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function japen()
    {
        
        $cache_key = $this->getCacheKeyPrefix() . 'ranking:japen:' . date('Y-m-d');

        //一日
        $cache_ttl = 86400;

        $data = Cache::remember($cache_key, $cache_ttl, function () {
            
            $rankings = Book::with(['latest_chapter'])
                        ->where([
                            ['status','1'],
                            ['review','1'],
                            ['type'  ,'1']
                        ])
                        ->orderByDesc('visits')
                        ->limit(100)
                        ->get();

            return $rankings->map(function ($ranking , $idx) {
                    return [
                        'book_id' => $ranking->id,
                        'ranking' => $idx+1,
                        'hits' => $ranking->visits,
                        'episode' => $ranking->latest_chapter->episode,
                        'title' => $ranking->title,
                        'description' => $ranking->description,
                        'end' => $ranking->end,
                        'vertical_cover' => $ranking->vertical_cover,
                        'horizontal_cover' => $ranking->horizontal_cover
                    ];
            })->values()->toArray();

        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function korea()
    {
        
        $cache_key = $this->getCacheKeyPrefix() . 'ranking:korea:' . date('Y-m-d');

        //一日
        $cache_ttl = 86400;

        $data = Cache::remember($cache_key, $cache_ttl, function () {

            $rankings = Book::with(['latest_chapter'])
                        ->where([
                            ['status','1'],
                            ['review','1'],
                            ['type'  ,'2']
                        ])
                        ->orderByDesc('visits')
                        ->limit(100)
                        ->get();

            return $rankings->map(function ($ranking , $idx) {
                    return [
                        'book_id' => $ranking->id,
                        'ranking' => $idx+1,
                        'hits' => $ranking->visits,
                        'episode' => $ranking->latest_chapter->episode,
                        'title' => $ranking->title,
                        'description' => $ranking->description,
                        'end' => $ranking->end,
                        'vertical_cover' => $ranking->vertical_cover,
                        'horizontal_cover' => $ranking->horizontal_cover
                    ];
            })->values()->toArray();

        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

    public function new()
    {
        
        $cache_key = $this->getCacheKeyPrefix() . 'ranking:new:' . date('Y-m-d');

        //一日
        $cache_ttl = 86400;

        $data = Cache::remember($cache_key, $cache_ttl, function () {

            $rankings = Book::with(['latest_chapter'])
                        ->where([
                            ['status','1'],
                            ['review','1']
                        ])
                        ->orderByDesc('created_at')
                        ->limit(100)
                        ->get();

            return $rankings->map(function ($ranking , $idx) {
                    return [
                        'book_id' => $ranking->id,
                        'ranking' => $idx+1,
                        'hits' => $ranking->visits,
                        'episode' => $ranking->latest_chapter->episode,
                        'title' => $ranking->title,
                        'description' => $ranking->description,
                        'end' => $ranking->end,
                        'vertical_cover' => $ranking->vertical_cover,
                        'horizontal_cover' => $ranking->horizontal_cover
                    ];
            })->values()->toArray();

        });

        return Response::jsonSuccess(__('api.success'), $data);
    }

}
