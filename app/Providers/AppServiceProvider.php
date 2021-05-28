<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * 设定所有的单例模式容器绑定的对应关系
     *
     * @var array
     */
    public $singletons = [
        'UploadService' => \App\Services\UploadService::class,
        'RecordService' => \App\Services\RecordService::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Schema::defaultStringLength(255);

        // redirect http to https
        if ($this->app->environment('production')) {
            Url::forceScheme('https');
            $this->app['request']->server->set('HTTPS','on');
        }
    }
}
