<?php

namespace App\Observers;

use App\Models\Video;

class VideoObserver
{
    /**
     * Handle the Video "created" event.
     *
     * @param  Video  $video
     * @return void
     */
    public function created(Video $video)
    {
        //
    }

    /**
     * Handle the Video "updated" event.
     *
     * @param  Video  $video
     * @return void
     */
    public function updated(Video $video)
    {
        //
    }

    /**
     * Handle the Video "deleted" event.
     *
     * @param  Video  $video
     * @return void
     */
    public function deleted(Video $video)
    {
        $video->visit_logs()->delete();
        $video->favorite_logs()->delete();
    }

    /**
     * Handle the Video "restored" event.
     *
     * @param  Video  $video
     * @return void
     */
    public function restored(Video $video)
    {
        //
    }

    /**
     * Handle the Video "force deleted" event.
     *
     * @param  Video  $video
     * @return void
     */
    public function forceDeleted(Video $video)
    {
        $video->visit_logs()->delete();
        $video->favorite_logs()->delete();
    }
}
