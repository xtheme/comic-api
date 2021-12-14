<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ApiRequestLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LogAnalysisController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date') ?? date('Y-m-d');
        $ip = $request->input('ip') ?? null;

        $total_request = ApiRequestLog::whereDate('created_at', $date)->when($ip, function (Builder $query, $ip) {
            return $query->where('ip', $ip);
        })->count() ?? 0;

        $avg_request_time = ApiRequestLog::whereDate('created_at', $date)->when($ip, function (Builder $query, $ip) {
                return $query->where('ip', $ip);
            })->avg('times') ?? 0;

        $max_request_time = ApiRequestLog::whereDate('created_at', $date)->when($ip, function (Builder $query, $ip) {
                return $query->where('ip', $ip);
            })->max('times') ?? 0;

        $min_request_time = ApiRequestLog::whereDate('created_at', $date)->when($ip, function (Builder $query, $ip) {
                return $query->where('ip', $ip);
            })->min('times') ?? 0;

        $ip_count = ApiRequestLog::distinct('ip')->whereDate('created_at', $date)->when($ip, function (Builder $query, $ip) {
                return $query->where('ip', $ip);
            })->count() ?? 0;

        $slow_logs = ApiRequestLog::whereDate('created_at', $date)->when($ip, function (Builder $query, $ip) {
            return $query->where('ip', $ip);
        })->orderByDesc('times')->paginate();

        $data = [
            'total_request' => $total_request,
            'avg_request_time' => (string) round($avg_request_time, 4),
            'max_request_time' => $max_request_time,
            'min_request_time' => $min_request_time,
            'ip_count' => $ip_count,
            'slow_logs' => $slow_logs,
        ];

        return view('backend.analysis.daily_analysis')->with($data);
    }
}
