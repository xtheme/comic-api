<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Cache;

class BookObserver
{
    /**
     * Handle the Book "created" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function created(Book $book)
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function updated(Book $book)
    {
        $this->forgetCache($book);
    }

    /**
     * Handle the Book "deleted" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function deleted(Book $book)
    {
        $book->chapters()->delete();
        $book->visit_logs()->delete();
        $book->favorite_logs()->delete();

        $this->forgetCache($book);
    }

    /**
     * Handle the Book "restored" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function restored(Book $book)
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function forceDeleted(Book $book)
    {
        $book->chapters()->delete();
        $book->visit_logs()->delete();
        $book->favorite_logs()->delete();

        $this->forgetCache($book);
    }

    public function forgetCache(Book $book)
    {
        $cache_key = sprintf('book:%s', $book->id);
        Cache::forget($cache_key);
    }
}
