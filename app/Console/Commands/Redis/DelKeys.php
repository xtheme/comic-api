<?php

namespace App\Console\Commands\Redis;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class DelKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:del {key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除指定的 Redis Key (使用時請移除 prefix)';

    protected $redis;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->redis = Redis::connection('cache');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->argument('key');

        $redis_keys = $this->redis->keys($key);

        foreach ($redis_keys as $redis_key) {
            $this->redis->del($this->clearPrefix($redis_key));
            $this->info('Redis key: ' . $redis_key . ' 已清除');
        }
    }

    /**
     * 使用原聲命令的 key name 必須移除 prefix
     */
    private function clearPrefix($key_name)
    {
        $prefix = config('database.redis.options.prefix');
        return Str::replaceFirst($prefix, '', $key_name);
    }
}
