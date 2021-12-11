<?php

namespace App\Jobs;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VisitBook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $book_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($book_id)
    {
        $this->book_id = $book_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Book::withoutEvents(function () {
            $book = Book::find($this->book_id);

            // 訪問數+1
            $book->increment('view_counts');

            // 添加到排行榜
            $book->logRanking();
        });
    }
}
