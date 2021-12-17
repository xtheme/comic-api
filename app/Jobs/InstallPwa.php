<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Channel;
use App\Models\ChannelDailyReport;
use App\Models\ChannelMonthlyReport;
use App\Traits\SendSentry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Throwable;

class InstallPwa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendSentry;

    private $channel_id;
    private $date;
    private $month;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($channel_id)
    {
        $this->channel_id = $channel_id;
        $this->date = date('Y-m-d');
        $this->month = date('Y-m-01');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->dailyChannel();
        $this->monthlyChannel();

        $channel = Channel::findOrFail($this->channel_id);
        $channel->increment('pwa_download_count');
    }

    private function dailyChannel()
    {
        $report = ChannelDailyReport::firstOrCreate([
            'channel_id' => $this->channel_id,
            'date' => $this->date,
        ]);

        $report->increment('pwa_download_count');
    }

    private function monthlyChannel()
    {
        $report = ChannelMonthlyReport::firstOrCreate([
            'channel_id' => $this->channel_id,
            'date' => $this->month,
        ]);

        $report->increment('pwa_download_count');
    }
}
