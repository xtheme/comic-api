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
        $this->app->bind(Contracts\CommentRepositoryInterface::class, Repositories\CommentRepository::class);
        $this->app->bind(Contracts\OrderRepositoryInterface::class, Repositories\OrderRepository::class);
        $this->app->bind(Contracts\BookRepositoryInterface::class, Repositories\BookRepository::class);
        $this->app->bind(Contracts\BookChapterRepositoryInterface::class, Repositories\BookChapterRepository::class);
        $this->app->bind(Contracts\TagRepositoryInterface::class, Repositories\TagRepository::class);
        $this->app->bind(Contracts\VideoRepositoryInterface::class, Repositories\VideoRepository::class);
        $this->app->bind(Contracts\VideoDomainRepositoryInterface::class, Repositories\VideoDomainRepository::class);
        $this->app->bind(Contracts\VideoSeriesRepositoryInterface::class, Repositories\VideoSeriesRepository::class);
        $this->app->bind(Contracts\AdSpaceRepositoryInterface::class, Repositories\AdSpaceRepository::class);
        $this->app->bind(Contracts\AdRepositoryInterface::class, Repositories\AdRepository::class);
        $this->app->bind(Contracts\BlockRepositoryInterface::class, Repositories\BlockRepository::class);
        $this->app->bind(Contracts\HistoryRepositoryInterface::class, Repositories\HistoryRepository::class);
        $this->app->bind(Contracts\MovieRepositoryInterface::class, Repositories\MovieRepository::class);
        $this->app->bind(Contracts\LadyRepositoryInterface::class, Repositories\LadyRepository::class);

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
