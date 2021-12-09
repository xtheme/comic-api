<?php

namespace App\Providers;

use App\Models;
use App\Observers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Models\Book::observe(Observers\BookObserver::class);
        Models\BookChapter::observe(Observers\BookChapterObserver::class);
        Models\Config::observe(Observers\ConfigObserver::class);
        Models\Filter::observe(Observers\FilterObserver::class);
        Models\Order::observe(Observers\OrderObserver::class);
        Models\User::observe(Observers\UserObserver::class);
        Models\Video::observe(Observers\VideoObserver::class);
    }
}
