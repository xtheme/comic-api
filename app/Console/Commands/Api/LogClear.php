<?php

namespace App\Console\Commands\Api;

use App\Models\ApiRequestLog;
use Illuminate\Console\Command;

class LogClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:log-clear {--days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除舊的 API 請求日誌';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');

        ApiRequestLog::whereDate('created_at', '<=', today()->subDays($days))->delete();

        $this->info('舊日誌已清除');

        return 0;
    }
}
