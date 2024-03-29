<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  User  $user
     *
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  User  $user
     *
     * @return void
     */
    public function updated(User $user)
    {

    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  User  $user
     *
     * @return void
     */
    public function deleted(User $user)
    {

    }

    /**
     * Handle the User "restored" event.
     *
     * @param  User  $user
     *
     * @return void
     */
    public function restored(User $user)
    {

    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  User  $user
     *
     * @return void
     */
    public function forceDeleted(User $user)
    {
        $user->orders()->delete();
        $user->recharge_logs()->delete();
        $user->purchase_logs()->delete();
        $user->visit_books()->delete();
    }
}
