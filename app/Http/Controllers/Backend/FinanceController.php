<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ChannelDailyReport;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function channelDaily($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $select = [
            'id',
            DB::raw('sum(register_count) as register_count'),
            DB::raw('sum(register_app_count) as register_app_count'),
            DB::raw('sum(register_wap_count) as register_wap_count'),
            DB::raw('sum(recharge_count) as recharge_count'),
            DB::raw('sum(wap_new_count) as wap_new_count'),
            DB::raw('sum(wap_renew_count) as wap_renew_count'),
            DB::raw('sum(wap_count) as wap_count'),
            DB::raw('sum(app_new_count) as app_new_count'),
            DB::raw('sum(app_renew_count) as app_renew_count'),
            DB::raw('sum(app_count) as app_count'),
            DB::raw('sum(recharge_amount) as recharge_amount'),
            DB::raw('sum(wap_new_amount) as wap_new_amount'),
            DB::raw('sum(wap_renew_amount) as wap_renew_amount'),
            DB::raw('sum(wap_amount) as wap_amount'),
            DB::raw('sum(app_new_amount) as app_new_amount'),
            DB::raw('sum(app_renew_amount) as app_renew_amount'),
            DB::raw('sum(app_amount) as app_amount'),
        ];

        $list = ChannelDailyReport::select($select)->whereDate('date', $date)->groupBy('channel_id')->get();

        $data = [
            'date' => $date,
            'list' => $list->toArray(),
        ];

        return view('backend.finance.channel_daily')->with($data);
    }

    public function channelDetail($channel_id, $date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $select = [
            'id',
            DB::raw('sum(register_count) as register_count'),
            DB::raw('sum(register_app_count) as register_app_count'),
            DB::raw('sum(register_wap_count) as register_wap_count'),
            DB::raw('sum(recharge_count) as recharge_count'),
            DB::raw('sum(wap_new_count) as wap_new_count'),
            DB::raw('sum(wap_renew_count) as wap_renew_count'),
            DB::raw('sum(wap_count) as wap_count'),
            DB::raw('sum(app_new_count) as app_new_count'),
            DB::raw('sum(app_renew_count) as app_renew_count'),
            DB::raw('sum(app_count) as app_count'),
            DB::raw('sum(recharge_amount) as recharge_amount'),
            DB::raw('sum(wap_new_amount) as wap_new_amount'),
            DB::raw('sum(wap_renew_amount) as wap_renew_amount'),
            DB::raw('sum(wap_amount) as wap_amount'),
            DB::raw('sum(app_new_amount) as app_new_amount'),
            DB::raw('sum(app_renew_amount) as app_renew_amount'),
            DB::raw('sum(app_amount) as app_amount'),
        ];

        $list = ChannelDailyReport::where('channel_id', $channel_id)->whereDate('date', $date)->get();

        $data = [
            'date' => $date,
            'list' => $list->toArray(),
        ];

        return view('backend.finance.channel_detail')->with($data);
    }
}
