<?php

namespace App\Console\Commands;

use App\Models\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '數據初始化';

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
        if ($this->confirm('是否確認要將數據初始化?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('migrations')->truncate();
            DB::table('personal_access_tokens')->truncate();
            DB::table('ranking_logs')->truncate();
            DB::table('users')->truncate();
            DB::table('user_favorite_logs')->truncate();
            DB::table('user_purchase_logs')->truncate();
            DB::table('user_recharge_logs')->truncate();
            DB::table('user_visit_logs')->truncate();
            DB::table('orders')->truncate();
            DB::table('channels')->truncate();
            DB::table('channel_daily_reports')->truncate();
            DB::table('channel_monthly_reports')->truncate();
            DB::table('payment_daily_reports')->truncate();
            DB::table('payment_monthly_reports')->truncate();
            DB::table('admins')->truncate();
            DB::table('model_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('permissions')->truncate();
            DB::table('roles')->truncate();
            DB::table('role_has_permissions')->truncate();
            DB::table('topics')->truncate();
            DB::table('navigation')->truncate();
            DB::table('payments')->truncate();
            DB::table('pricings')->truncate();
            DB::table('categories')->truncate();
            DB::table('tags')->truncate();
            DB::table('configs')->truncate();
            DB::table('activity_log')->truncate();
            DB::unprepared(file_get_contents(database_path('sql/install.sql')));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('所有數據已還原至初始狀態!');
    }
}
