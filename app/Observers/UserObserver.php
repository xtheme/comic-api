<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserService;
use App\Traits\CacheTrait;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    use CacheTrait;

    private function overrideCache(User $user)
    {
        // todo 未決定建立緩存時機
        // app(UserService::class)->updateUserCache($user);
    }

    /**
     * Handle the User "created" event.
     *
     * @param  User $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  User $user
     * @return void
     */
    public function updated(User $user)
    {
        $this->overrideCache($user);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  User $user
     * @return void
     */
    public function deleted(User $user)
    {
        $this->overrideCache($user);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  User $user
     * @return void
     */
    public function restored(User $user)
    {
        $this->overrideCache($user);
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        $user->orders()->delete();
        $user->signs()->delete();
        $user->book_visit_histories()->delete();
        $user->video_visit_histories()->delete();
        $user->video_play_histories()->delete();
        // todo delete user comments

        $this->overrideCache($user);
    }
}
