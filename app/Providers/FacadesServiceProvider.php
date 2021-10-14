<?php

namespace App\Providers;

use App\Services;
use Illuminate\Support\ServiceProvider;

class FacadesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('UploadService', function () {
            return new Services\UploadService();
        });

        $this->app->bind('RecordService', function () {
            return new Services\RecordService();
        });

        $this->app->bind('SsoService', function () {
            return new Services\SsoService();
        });

        $this->app->bind('GatewayService', function () {
            return new Services\GatewayService();
        });
    }
}
