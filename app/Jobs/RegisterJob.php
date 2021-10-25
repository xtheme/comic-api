<?php

namespace App\Jobs;

use App\Models\Channel;
use App\Models\ChannelDailyReport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class RegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $platform;

    public function __construct(User $user, $platform)
    {
        $this->user = $user->withoutRelations();
        $this->platform = $platform;
    }

    public function handle()
    {
        $channel_id = $this->user->channel_id;

        $daily = ChannelDailyReport::firstOrCreate([
            'channel_id' => $channel_id,
            'date' => date('Y-m-d'),
            'hour' => date('h'),
        ]);
        $daily->increment('register_count');
        $daily->increment(sprintf('register_%s_count', $this->platform));


        $channel = Channel::findOrFail($channel_id);
        $channel->increment('register_count');
        $channel->increment(sprintf('register_%s_count', $this->platform));
    }
}
