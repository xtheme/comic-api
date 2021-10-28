<?php

namespace App\Console\Commands;

use App\Models\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ResetReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '報表數據初始化';

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
     */
    public function handle()
    {
        if ($this->confirm('是否確認要將報表數據初始化?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('channel_daily_reports')->truncate();
            DB::table('channel_monthly_reports')->truncate();
            DB::table('payment_daily_reports')->truncate();
            DB::table('payment_monthly_reports')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $redis = Redis::connection('readonly');

        $keys = $redis->keys('payment*');

        if ($keys) {
            $redis->del($keys);
        }

        $keys = $redis->keys('channel*');

        if ($keys) {
            $redis->del($keys);
        }

        DB::table('channels')->update([
            'register_count' => 0,
            'recharge_count' => 0,
            'recharge_amount' => 0,
            'wap_register_count' => 0,
            'app_register_count' => 0,
            'app_download_count' => 0,
            'wap_new_count' => 0,
            'app_new_count' => 0,
            'new_count' => 0,
            'wap_renew_count' => 0,
            'app_renew_count' => 0,
            'renew_count' => 0,
            'wap_new_amount' => 0,
            'app_new_amount' => 0,
            'new_amount' => 0,
            'wap_renew_amount' => 0,
            'app_renew_amount' => 0,
            'renew_amount' => 0,
        ]);

        $this->info('所有數據已還原至初始狀態!');
    }
}
