<?php

namespace App\Providers;

use App\Gateways;
use Illuminate\Support\ServiceProvider;

class GatewayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('GoddessGateway', function () {
            return new Gateways\GoddessGateway();
        });

        $this->app->singleton('InterestGateway', function () {
            return new Gateways\InterestGateway();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
