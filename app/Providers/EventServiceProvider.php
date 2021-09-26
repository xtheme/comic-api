<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Config;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoSeries;
use App\Observers\BookObserver;
use App\Observers\ConfigObserver;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
use App\Observers\VideoObserver;
use App\Observers\VideoSeriesObserver;
// use Conner\Tagging\Model\Tag;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        Config::observe(ConfigObserver::class);
        // Tag::observe(TagObserver::class);
        User::observe(UserObserver::class);
        Book::observe(BookObserver::class);
        Video::observe(VideoObserver::class);
        VideoSeries::observe(VideoSeriesObserver::class);
    }
}
