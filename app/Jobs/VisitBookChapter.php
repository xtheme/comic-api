<?php

namespace App\Jobs;

use App\Models\BookChapter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VisitBookChapter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $chapter_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chapter_id)
    {
        $this->chapter_id = $chapter_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        BookChapter::withoutEvents(function () {
            $chapter = BookChapter::find($this->chapter_id);
            // 訪問數+1
            $chapter->increment('view_counts');
        });
    }
}
