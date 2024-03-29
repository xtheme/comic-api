<?php

namespace App\Providers;

use App\Repositories;
use App\Repositories\Contracts;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Contracts\UserRepositoryInterface::class, Repositories\UserRepository::class);
        $this->app->bind(Contracts\VideoRepositoryInterface::class, Repositories\VideoRepository::class);
        $this->app->bind(Contracts\TopicRepositoryInterface::class, Repositories\TopicRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }
}
