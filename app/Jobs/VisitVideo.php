<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\User;
use App\Models\Video;
use App\Traits\SendSentry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VisitVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendSentry;

    private int $video_id;
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($video_id, $user)
    {
        $this->video_id = $video_id;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Video::withoutEvents(function () {
            $video = Video::find($this->video_id);

            // 訪問數+1
            $video->increment('view_counts');

            // 添加到排行榜
            $video->logRanking();

            // 記錄用戶訪問
            if ($this->user) {
                $this->user->visit($video);
            }
        });
    }
}
