<?php

namespace App\Observers;

use App\Models\Filter;
use Illuminate\Support\Facades\Cache;

class FilterObserver
{
    /**
     * Handle the Book "created" event.
     *
     * @param  \App\Models\Filter  $filter
     * @return void
     */
    public function created(Filter $filter)
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     *
     * @param  \App\Models\Filter  $filter
     * @return void
     */
    public function updated(Filter $filter)
    {
        Cache::tags(['filter', $filter->id])->flush();
    }

    /**
     * Handle the Book "deleted" event.
     *
     * @param  \App\Models\Filter  $filter
     * @return void
     */
    public function deleted(Filter $filter)
    {
        Cache::tags(['filter', $filter->id])->flush();
    }

    /**
     * Handle the Book "restored" event.
     *
     * @param  \App\Models\Filter  $filter
     * @return void
     */
    public function restored(Filter $filter)
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     *
     * @param  \App\Models\Filter  $filter
     * @return void
     */
    public function forceDeleted(Filter $filter)
    {
    }
}
