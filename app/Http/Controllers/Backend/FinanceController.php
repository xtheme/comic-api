<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChannelDailyReport;
use App\Models\PaymentDailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function channelDaily(Request $request)
    {
        $date = $request->input('date') ?? date('Y-m-d');

        $select = [
            'channel_id',
            DB::raw('SUM(recharge_amount) as recharge_amount'),
            DB::raw('SUM(register_count) as register_count'),
            DB::raw('SUM(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('SUM(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('SUM(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('SUM(wap_new_amount) as wap_new_amount'),
            DB::raw('SUM(app_new_amount) as app_new_amount'),
            DB::raw('SUM(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('SUM(app_download_count) as app_download_count'),
        ];

        $list = ChannelDailyReport::select($select)->whereDate('date', $date)->groupBy('channel_id')->paginate(50);

        // 當日汇总
        $select = [
            DB::raw('SUM(recharge_amount) as recharge_amount'),
            DB::raw('SUM(register_count) as register_count'),
            DB::raw('SUM(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('SUM(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('SUM(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('SUM(wap_new_amount) as wap_new_amount'),
            DB::raw('SUM(app_new_amount) as app_new_amount'),
            DB::raw('SUM(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('SUM(app_download_count) as app_download_count'),
        ];

        $total = ChannelDailyReport::select($select)->whereDate('date', $date)->groupBy('date')->first();

        $data = [
            'date' => $date,
            'list' => $list,
            'total' => $total,
        ];

        return view('backend.finance.channel_daily')->with($data);
    }

    public function channelDetail($channel_id)
    {
        $select = [
            'date',
            DB::raw('SUM(recharge_amount) as recharge_amount'),
            DB::raw('SUM(register_count) as register_count'),
            DB::raw('SUM(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('SUM(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('SUM(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('SUM(wap_new_amount) as wap_new_amount'),
            DB::raw('SUM(app_new_amount) as app_new_amount'),
            DB::raw('SUM(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('SUM(app_download_count) as app_download_count'),
        ];

        $list = ChannelDailyReport::select($select)->where('channel_id', $channel_id)->groupBy('date')->latest('date')->paginate(50);

        $data = [
            'list' => $list,
        ];

        return view('backend.finance.channel_detail')->with($data);
    }

    public function daily()
    {
        $select = [
            'date',
            DB::raw('SUM(recharge_amount) as recharge_amount'),
            DB::raw('SUM(register_count) as register_count'),
            DB::raw('SUM(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('SUM(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('SUM(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('SUM(wap_new_amount) as wap_new_amount'),
            DB::raw('SUM(app_new_amount) as app_new_amount'),
            DB::raw('SUM(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('SUM(app_download_count) as app_download_count'),
        ];

        $list = ChannelDailyReport::select($select)->groupBy('date')->latest('date')->paginate(50);

        $data = [
            'list' => $list,
        ];

        return view('backend.finance.daily')->with($data);
    }

    public function totalRevenue()
    {
        $select = [
            DB::raw('DATE_FORMAT(date, "%Y/%m/%d") as date'),
            DB::raw('SUM(recharge_amount) as recharge_amount'),
        ];

        $list = ChannelDailyReport::select($select)->groupBy('date')->latest('date')->paginate(50);

        $data = [
            'list' => $list,
        ];

        return view('backend.finance.total_revenue')->with($data);
    }

    public function gatewayRevenue(Request $request)
    {
        $date = $request->input('date') ?? date('Y-m-d');

        $list = PaymentDailyReport::with(['payment'])->whereDate('date', $date)->groupBy('payment_id')->latest('payment_id')->paginate(50);

        $data = [
            'date' => $date,
            'list' => $list,
        ];

        return view('backend.finance.gateway_revenue')->with($data);
    }
}
