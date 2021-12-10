<?php

namespace App\Observers;

use App\Models\Config;
use Illuminate\Support\Facades\Cache;

class ConfigObserver
{
    /**
     * Handle the Config "created" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function created(Config $config)
    {
        $this->forgetCache($config);
    }

    /**
     * Handle the Config "updated" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function updated(Config $config)
    {
        $this->forgetCache($config);
    }

    /**
     * Handle the Config "deleted" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function deleted(Config $config)
    {
        $this->forgetCache($config);
    }

    /**
     * Handle the Config "restored" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function restored(Config $config)
    {
        $this->forgetCache($config);
    }

    /**
     * Handle the Config "force deleted" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function forceDeleted(Config $config)
    {
        $this->forgetCache($config);
    }

    public function forgetCache(Config $config)
    {
        $cache_key = sprintf('config:%s', $config->code);
        Cache::forget($cache_key);
    }
}
