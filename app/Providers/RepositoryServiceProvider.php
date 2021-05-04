<?php

namespace App\Providers;

use App\Repositories\AdSpaceRepository;
use App\Repositories\BlockRepository;
use App\Repositories\BookChapterRepository;
use App\Repositories\BookRepository;
use App\Repositories\CommentRepository;
use App\Repositories\Contracts\AdSpaceRepositoryInterface;
use App\Repositories\Contracts\BlockRepositoryInterface;
use App\Repositories\Contracts\BookChapterRepositoryInterface;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\VideoDomainRepositoryInterface;
use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Repositories\Contracts\VideoSeriesRepositoryInterface;
use App\Repositories\Contracts\AdRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\VideoDomainRepository;
use App\Repositories\VideoRepository;
use App\Repositories\VideoSeriesRepository;
use App\Repositories\AdRepository;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookChapterRepositoryInterface::class, BookChapterRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(VideoDomainRepositoryInterface::class, VideoDomainRepository::class);
        $this->app->bind(VideoSeriesRepositoryInterface::class, VideoSeriesRepository::class);
        $this->app->bind(AdSpaceRepositoryInterface::class, AdSpaceRepository::class);
        $this->app->bind(AdRepositoryInterface::class, AdRepository::class);
        $this->app->bind(BlockRepositoryInterface::class, BlockRepository::class);
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
