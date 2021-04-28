<?php

namespace App\Providers;

use App\Repositories\BookCategoryRepository;
use App\Repositories\BookChapterRepository;
use App\Repositories\BookRepository;
use App\Repositories\CommentRepository;
use App\Repositories\ContentRepository;
use App\Repositories\Contracts\BookCategoryRepositoryInterface;
use App\Repositories\Contracts\BookChapterRepositoryInterface;
use App\Repositories\Contracts\BookRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\VideoDomainRepositoryInterface;
use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Repositories\Contracts\VideoSeriesRepositoryInterface;
use App\Repositories\Contracts\VideoAdRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\ContentRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\VideoDomainRepository;
use App\Repositories\VideoRepository;
use App\Repositories\VideoSeriesRepository;
use App\Repositories\VideoAdRepository;
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
        $this->app->bind(ContentRepositoryInterface::class, ContentRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(BookChapterRepositoryInterface::class, BookChapterRepository::class);
        $this->app->bind(BookCategoryRepositoryInterface::class, BookCategoryRepository::class);
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(VideoDomainRepositoryInterface::class, VideoDomainRepository::class);
        $this->app->bind(VideoSeriesRepositoryInterface::class, VideoSeriesRepository::class);
        $this->app->bind(VideoAdRepositoryInterface::class, VideoAdRepository::class);
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
