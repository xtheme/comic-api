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
        if ($this->confirm('是否建立預設帳號與權限?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('activity_log')->truncate();
            DB::table('admins')->truncate();
            DB::table('model_has_permissions')->truncate();
            DB::table('model_has_roles')->truncate();
            DB::table('permissions')->truncate();
            DB::table('roles')->truncate();
            DB::table('role_has_permissions')->truncate();
            DB::unprepared(file_get_contents(database_path('sql/init_permissions.sql')));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        if ($this->confirm('是否初始化所有用戶相關數據?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('personal_access_tokens')->truncate();
            DB::table('users')->truncate();
            DB::table('user_favorite_logs')->truncate();
            DB::table('user_purchase_logs')->truncate();
            DB::table('user_recharge_logs')->truncate();
            DB::table('user_visit_logs')->truncate();
            DB::table('orders')->truncate();
            DB::table('ranking_logs')->truncate();
            DB::table('reports')->truncate();
            DB::table('comment_likes')->truncate();
            DB::table('comments')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        if ($this->confirm('是否要初始化配置項?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('configs')->truncate();
            DB::unprepared(file_get_contents(database_path('sql/init_config.sql')));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        if ($this->confirm('是否要將價格方案與支付渠道初始化?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('pricings')->truncate();
            DB::unprepared(file_get_contents(database_path('sql/init_pricing.sql')));
            DB::table('payments')->truncate();
            DB::unprepared(file_get_contents(database_path('sql/init_payment.sql')));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        if ($this->confirm('是否確認要將廣告渠道與財務報表初始化?')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('channels')->truncate();
            DB::table('channel_daily_reports')->truncate();
            DB::table('channel_monthly_reports')->truncate();
            DB::table('payment_daily_reports')->truncate();
            DB::table('payment_monthly_reports')->truncate();
            DB::unprepared(file_get_contents(database_path('sql/init_channel.sql')));
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('所有數據已還原至初始狀態!');
    }
}
