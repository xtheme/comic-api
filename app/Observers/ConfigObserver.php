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
        $cache_key = 'config:' . $config->code;

        Cache::forever($cache_key, $config->content);
    }

    /**
     * Handle the Config "updated" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function updated(Config $config)
    {
        $cache_key = 'config:' . $config->code;

        Cache::forever($cache_key, $config->content);
    }

    /**
     * Handle the Config "deleted" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function deleted(Config $config)
    {
        $cache_key = 'config:' . $config->code;

        Cache::forget($cache_key);
    }

    /**
     * Handle the Config "restored" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function restored(Config $config)
    {
        $cache_key = 'config:' . $config->code;

        Cache::forever($cache_key, $config->content);
    }

    /**
     * Handle the Config "force deleted" event.
     *
     * @param  \App\Models\Config  $config
     * @return void
     */
    public function forceDeleted(Config $config)
    {
        $cache_key = 'config:' . $config->code;

        Cache::forget($cache_key);
    }
}
