<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends BaseController
{
    private $page_size = 20;

    public function order(Request $request, $page = 1)
    {
        $user = $request->user();

        $logs = $user->orders()->forPage($page, $this->page_size)->get();

        $response = $logs->map(function ($log) {
            return [
                'order_no' => $log->order_no,
                'type' => OrderOptions::TYPE_OPTIONS[$log->type],
                'amount' => $log->amount,
                'plan' => $log->plan_options,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                'status' => OrderOptions::STATUS_OPTIONS[$log->status],
                'transaction_at' => optional($log->transaction_at)->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $response);
    }

    public function recharge(Request $request, $page = 1)
    {
        $user = $request->user();

        $logs = $user->recharge_logs()->forPage($page, $this->page_size)->get();

        $response = $logs->map(function ($log) {
            return [
                'order_no' => $log->order_no,
                'type' => OrderOptions::TYPE_OPTIONS[$log->type],
                'coin' => $log->coin,
                'gift_coin' => $log->gift_coin,
                'days' => $log->days,
                'gift_days' => $log->gift_days,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $response);
    }

    public function purchase(Request $request, $type, $page = 1)
    {
        $user = $request->user();

        $logs = $user->purchase_logs()->with([$type])->where('type', $type)->forPage($page, $this->page_size)->get();

        $response = $logs->map(function ($log) {
            return [
                'event' => $log->event,
                'title' => $log->item_title,
                'price' => $log->item_price,
                'created_at' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        })->toArray();

        return Response::jsonSuccess(__('api.success'), $response);
    }
}
