<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\ChannelDailyReport;
use App\Models\ChannelMonthlyReport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class RegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $platform;
    private $redis;
    private $channel_id;
    private $date;
    private $hour;
    private $month;

    public function __construct(User $user, $platform)
    {
        $this->user = $user->withoutRelations();
        $this->platform = $platform;
        $this->redis = Redis::connection('readonly');
        $this->channel_id = $this->user->channel_id;
        $this->date = date('Y-m-d');
        $this->hour = date('h');
        $this->month = date('Y-m-01');
    }

    public function handle()
    {
        $this->daily();
        $this->monthly();

        $channel = Channel::findOrFail($this->channel_id);
        $channel->increment('register_count');
        $channel->increment(sprintf('register_%s_count', $this->platform));
    }

    private function daily()
    {
        $report = ChannelDailyReport::firstOrCreate([
            'channel_id' => $this->channel_id,
            'date' => $this->date,
            'hour' => $this->hour,
        ]);
        $report->increment('register_count');
        $report->increment(sprintf('register_%s_count', $this->platform));

        // Redis 備份
        $redis_key = sprintf('channel:daily:%s:%s', $this->date, $this->hour);
        $this->redis->set($redis_key, json_encode($report->toArray(), JSON_UNESCAPED_UNICODE));
    }

    private function monthly()
    {
        $report = ChannelMonthlyReport::firstOrCreate([
            'channel_id' => $this->channel_id,
            'date' => $this->month,
        ]);
        $report->increment('register_count');
        $report->increment(sprintf('register_%s_count', $this->platform));

        // Redis 備份
        $redis_key = sprintf('channel:monthly:%s', date('Y-m', strtotime($this->month)));
        $this->redis->set($redis_key, json_encode($report->toArray(), JSON_UNESCAPED_UNICODE));
    }
}
