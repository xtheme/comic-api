<?php

namespace App\Console\Commands\Refactor;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Users extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重構 users 資料表結構';

    protected $table = 'users_clone';

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
        Schema::table('users_clone', function (Blueprint $table) {
            // 移除索引
            $table->dropIndex('openid');
            $table->dropIndex('gzopenid');
            $table->dropIndex('payopenid');
            $table->dropIndex('tgid');
            $table->dropIndex('did');
            $table->dropIndex('sxid');
            $this->line('弃用索引已移除');

            // 移除欄位
            $columns = [
                'name',
                'nickname',
                'password',
                'email',
                'email_bind',
                'avatar',
                'money',
                'role',
                'group',
                'sort',
                'openid',
                'did',
                'isvip',
                'vipstime',
                'vipetime',
                'fcbl',
                'xingming',
                'fangshi',
                'zhanghao',
                'tgid',
                'guanzhu',
                'ewm',
                'isguanzhu',
                'sxid',
                'isout',
                'gzopenid',
                'agentlogin',
                'payopenid',
                'zjgz',
                'idnumber',
                'qq_id',
                'qq_uid',
                'weibo_id',
                'weixin_uid',
                'weixin_id',
                'auto_buy',
                'user_type',
                'tzurl',
                'device_tokens',
                'cover_img',
                'invite_uid',
                'invite_install_code',
                'version_type',
            ];
            $table->dropColumn($columns);
            $this->line('弃用字段已移除');

            // 加上時間戳
            $table->timestamps();
            $this->line('添加 Laravel 时间戳');

            $table->timestamp('last_login_at')->after('last_login_time')->nullable();
            $this->line('添加 last_login_at 字段');
        });
        $this->line('数据表结构异动完成');

        DB::statement(sprintf('ALTER TABLE %s CHANGE `subscribed_at` `subscribed_at` TIMESTAMP NULL DEFAULT NULL', DB::getTablePrefix() . $this->table));
        $this->line('subscribed_at 属性已变更为 timestamp');

        DB::table($this->table)->whereNull('created_at')->update(['created_at' => DB::raw('FROM_UNIXTIME(`create_time`)')]);
        $this->line('created_at 数据已填充');

        DB::table($this->table)->whereNull('updated_at')->where('update_time', '!=', 0)->update(['updated_at' => DB::raw('FROM_UNIXTIME(`update_time`)')]);
        $this->line('updated_at 数据已填充');

        DB::table($this->table)->whereNull('last_login_at')->where('last_login_time', '!=', 0)->update(['last_login_at' => DB::raw('FROM_UNIXTIME(`last_login_time`)')]);
        $this->line('last_login_at 数据已填充');

        Schema::table('users_clone', function (Blueprint $table) {
            // 移除欄位
            $columns = [
                'last_login_time',
                'create_time',
                'update_time',
            ];
            $table->dropColumn($columns);
            $this->line('重复字段已排除');
        });

        $this->line('数据表结构已重构');

        return 0;
    }
}