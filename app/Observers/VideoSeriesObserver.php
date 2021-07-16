<?php

namespace App\Observers;

use App\Models\VideoSeries;

class VideoSeriesObserver
{
    /**
     * Handle the VideoSeries "created" event.
     *
     * @param  \App\Models\VideoSeries  $videoSeries
     * @return void
     */
    public function created(VideoSeries $videoSeries)
    {
        //
    }

    /**
     * Handle the VideoSeries "updated" event.
     *
     * @param  \App\Models\VideoSeries  $videoSeries
     * @return void
     */
    public function updated(VideoSeries $videoSeries)
    {
        //
    }

    /**
     * Handle the VideoSeries "deleted" event.
     *
     * @param  \App\Models\VideoSeries  $videoSeries
     * @return void
     */
    public function deleted(VideoSeries $videoSeries)
    {
        $videoSeries->play_histories()->delete();
    }

    /**
     * Handle the VideoSeries "restored" event.
     *
     * @param  \App\Models\VideoSeries  $videoSeries
     * @return void
     */
    public function restored(VideoSeries $videoSeries)
    {
        //
    }

    /**
     * Handle the VideoSeries "force deleted" event.
     *
     * @param  \App\Models\VideoSeries  $videoSeries
     * @return void
     */
    public function forceDeleted(VideoSeries $videoSeries)
    {
        $videoSeries->play_histories()->delete();
    }
}
