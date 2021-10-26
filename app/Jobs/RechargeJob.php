<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\ChannelDailyReport;
use App\Models\ChannelMonthlyReport;
use App\Models\Order;
use App\Models\PaymentDailyReport;
use App\Models\PaymentMonthlyReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class RechargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $amount;
    private $platform;
    private $first;
    private $payment_id;
    private $channel_id;

    private $date;
    private $hour;
    private $month;

    public function __construct(Order $order)
    {
        $order = $order->withoutRelations();
        $this->amount = $order->amount;
        $this->platform = $order->platform;
        $this->first = ($order->first == 1) ? 'new' : 'renew';
        $this->payment_id = $order->payment_id;
        $this->channel_id = $order->channel_id;

        $this->date = date('Y-m-d');
        $this->hour = date('h');
        $this->month = date('Y-m-01');
    }

    public function handle()
    {
        $this->dailyPayment();
        $this->dailyChannel();
        $this->monthlyPayment();
        $this->monthlyChannel();

        $channel = Channel::findOrFail($this->channel_id);
        $channel->increment('recharge_count');
        $channel->increment(sprintf('recharge_%s_count', $this->platform));
        $channel->increment('recharge_amount', $this->amount);
        $channel->increment(sprintf('recharge_%s_amount', $this->platform), $this->amount);
    }

    private function incrementReport($report)
    {
        $report->increment(sprintf('%s_%s_count', $this->platform, $this->first));
        $report->increment(sprintf('%s_count', $this->platform));
        $report->increment('recharge_count');
        $report->increment(sprintf('%s_%s_amount', $this->platform, $this->first), $this->amount);
        $report->increment(sprintf('%s_amount', $this->platform), $this->amount);
        $report->increment('recharge_amount', $this->amount);
    }

    private function dailyPayment()
    {
        $report = PaymentDailyReport::firstOrCreate([
            'payment_id' => $this->payment_id,
            'date' => $this->date,
            'hour' => $this->hour,
        ]);

        $this->incrementReport($report);

        // Redis 備份
        $redis = Redis::connection('readonly');
        $redis_key = sprintf('payment:daily:%s:%s', $this->date, $this->hour);
        $redis->set($redis_key, json_encode($report->toArray(), JSON_UNESCAPED_UNICODE));
    }

    private function dailyChannel()
    {
        $report = ChannelDailyReport::firstOrCreate([
            'channel_id' => $this->channel_id,
            'date' => $this->date,
            'hour' => $this->hour,
        ]);

        $this->incrementReport($report);

        // Redis 備份
        $redis = Redis::connection('readonly');
        $redis_key = sprintf('channel:daily:%s:%s', $this->date, $this->hour);
        $redis->set($redis_key, json_encode($report->toArray(), JSON_UNESCAPED_UNICODE));
    }

    private function monthlyPayment()
    {
        $report = PaymentMonthlyReport::firstOrCreate([
            'payment_id' => $this->payment_id,
            'date' => $this->month,
        ]);

        $this->incrementReport($report);

        // Redis 備份
        $redis = Redis::connection('readonly');
        $redis_key = sprintf('payment:monthly:%s', date('Y-m', strtotime($this->month)));
        $redis->set($redis_key, json_encode($report->toArray(), JSON_UNESCAPED_UNICODE));
    }

    private function monthlyChannel()
    {
        $report = ChannelMonthlyReport::firstOrCreate([
            'channel_id' => $this->channel_id,
            'date' => $this->month,
        ]);

        $this->incrementReport($report);

        // Redis 備份
        $redis = Redis::connection('readonly');
        $redis_key = sprintf('channel:monthly:%s', date('Y-m', strtotime($this->month)));
        $redis->set($redis_key, json_encode($report->toArray(), JSON_UNESCAPED_UNICODE));
    }
}
