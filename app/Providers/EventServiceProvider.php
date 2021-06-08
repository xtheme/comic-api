<?php

namespace App\Providers;

use App\Models\Config;
use App\Models\User;
use App\Observers\ConfigObserver;
use App\Observers\TagObserver;
use App\Observers\UserObserver;
use Conner\Tagging\Model\Tag;
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
        Tag::observe(TagObserver::class);
        User::observe(UserObserver::class);
    }
}
