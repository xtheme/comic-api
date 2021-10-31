<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChannelDailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function channelDaily(Request $request)
    {
        $date = $request->input('date') ?? date('Y-m-d');

        $select = [
            'channel_id',
            DB::raw('sum(recharge_amount) as recharge_amount'),
            DB::raw('sum(register_count) as register_count'),
            DB::raw('sum(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('sum(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('sum(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('sum(wap_new_amount) as wap_new_amount'),
            DB::raw('sum(app_new_amount) as app_new_amount'),
            DB::raw('sum(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('sum(app_download_count) as app_download_count'),
        ];

        $list = ChannelDailyReport::select($select)->whereDate('date', $date)->groupBy('channel_id')->paginate(50);

        // 當日汇总
        $select = [
            DB::raw('sum(recharge_amount) as recharge_amount'),
            DB::raw('sum(register_count) as register_count'),
            DB::raw('sum(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('sum(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('sum(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('sum(wap_new_amount) as wap_new_amount'),
            DB::raw('sum(app_new_amount) as app_new_amount'),
            DB::raw('sum(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('sum(app_download_count) as app_download_count'),
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
            DB::raw('sum(recharge_amount) as recharge_amount'),
            DB::raw('sum(register_count) as register_count'),
            DB::raw('sum(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('sum(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('sum(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('sum(wap_new_amount) as wap_new_amount'),
            DB::raw('sum(app_new_amount) as app_new_amount'),
            DB::raw('sum(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('sum(app_download_count) as app_download_count'),
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
            DB::raw('sum(recharge_amount) as recharge_amount'),
            DB::raw('sum(register_count) as register_count'),
            DB::raw('sum(wap_new_amount + wap_renew_amount) as wap_amount'),
            DB::raw('sum(app_new_amount + app_renew_amount) as app_amount'),
            DB::raw('sum(app_new_amount + wap_new_amount) as new_amount'),
            DB::raw('sum(wap_new_amount) as wap_new_amount'),
            DB::raw('sum(app_new_amount) as app_new_amount'),
            DB::raw('sum(wap_renew_amount + app_renew_amount) as renew_amount'),
            DB::raw('sum(app_download_count) as app_download_count'),
        ];

        $list = ChannelDailyReport::select($select)->groupBy('date')->latest('date')->paginate(50);

        $data = [
            'list' => $list,
        ];

        return view('backend.finance.daily')->with($data);
    }
}
