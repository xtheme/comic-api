<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        return redirect()->route('backend.dashboard');
    }

    public function dashboard()
    {
        $data = [
            'statistics' => $this->statistics()
        ];

        return view('backend.dashboard')->with($data);
    }

    /**
     * 统计数据
     */
    private function statistics()
    {
        $statistics = [];

        $today = today();

        // 總用戶數
        $cache_key = 'statistics:total_users';
        $statistics['total_users'] = Cache::remember($cache_key, 300, function () {
            return User::where('is_active', 1)->count();
        });

        // 今日註冊人數
        $cache_key = 'statistics:today_register';
        $statistics['today_register'] = Cache::remember($cache_key, 300, function () use ($today) {
            return User::where('is_active', 1)->whereDate('created_at', $today)->count();
        });

        // 今日登入人數
        $cache_key = 'statistics:today_login';
        $statistics['today_login'] = Cache::remember($cache_key, 300, function () use ($today) {
            return User::where('is_active', 1)->whereDate('logged_at', $today)->count();
        });

        // 今日訂單數
        $cache_key = 'statistics:today_orders_count';
        $statistics['today_orders_count'] = Cache::remember($cache_key, 300, function () use ($today) {
            return Order::where('status', 1)->whereDate('created_at', $today)->count();
        });

        // 今日訂單金額
        $cache_key = 'statistics:today_orders_amount';
        $statistics['today_orders_amount'] = Cache::remember($cache_key, 300, function () use ($today) {
            return Order::where('status', 1)->whereDate('created_at', $today)->sum('amount');
        });

        // 有效漫畫數
        $cache_key = 'statistics:total_books';
        $statistics['total_books'] = Cache::remember($cache_key, 300, function () use ($today) {
            return Book::where('status', 1)->count();
        });

        return $statistics;
    }

    /**
     * 近两年用户成长
     */
    public function userGrowth()
    {
        /*
            [{
                name: '2019',
                data: [80, 95, 150, 210, 140, 230, 300, 280, 130]
            }, {
                name: '2018',
                data: [50, 70, 130, 180, 90, 180, 270, 220, 110]
            }]
        */

        $current_year = date('Y');

        $growth = [];
        for ($i = 0; $i < 2; $i++) {
            $year = $current_year - $i;
            $growth[] = [
                'name' => $year . ' 注册人数',
                'data' => $this->getUserGrowthByYear($year),
            ];
        }

        return $growth;
    }

    /**
     * 用户成长
     */
    private function getUserGrowthByYear($year)
    {
        $arr = [];

        for ($i = 1; $i <= 12; $i++) {
            $redis_key = sprintf('user_growth:%s-%s', $year, $i);

            if (date('Y-n') != $year . '-' . $i) {
                if (Cache::has($redis_key)) {
                    $arr[] = Cache::get($redis_key);
                    continue;
                }
            }

            $from = Carbon::parse(sprintf('first day of %s-%s', $year, $i))->toDateTimeString();
            $to = Carbon::parse(sprintf('last day of %s-%s', $year, $i))->toDateTimeString();
            $count = (string) User::whereBetween('created_at', [$from, $to])->count();

            Cache::put($redis_key, $count);

            $arr[] = $count;
        }

        return $arr;
    }
}
