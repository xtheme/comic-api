<?php

namespace App\Observers;

use App\Models\BookChapter;
use Illuminate\Support\Facades\Cache;

class BookChapterObserver
{
    /**
     * Handle the Book "created" event.
     *
     * @param  \App\Models\BookChapter  $chapter
     * @return void
     */
    public function created(BookChapter $chapter)
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     *
     * @param  \App\Models\BookChapter  $chapter
     * @return void
     */
    public function updated(BookChapter $chapter)
    {
        $cache_key = sprintf('chapter:%s', $chapter->id);
        Cache::delete($cache_key);
    }

    /**
     * Handle the Book "deleted" event.
     *
     * @param  \App\Models\BookChapter  $chapter
     * @return void
     */
    public function deleted(BookChapter $chapter)
    {
        $cache_key = sprintf('chapter:%s', $chapter->id);
        Cache::delete($cache_key);
    }

    /**
     * Handle the Book "restored" event.
     *
     * @param  \App\Models\BookChapter  $chapter
     * @return void
     */
    public function restored(BookChapter $chapter)
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     *
     * @param  \App\Models\BookChapter  $chapter
     * @return void
     */
    public function forceDeleted(BookChapter $chapter)
    {
    }
}
