<?php

namespace App\Console\Commands\Migrate;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CommentMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重构 comments，comment_zen 数据表结构, 执行复制到新表!';

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

        // 新舊數據表名稱
        $old_table = 'comments';
        $new_table = 'comments_clone';

        // 舊表變更名稱
        Schema::rename($old_table, $old_table . '_backup');
        // 新表變更名稱
        Schema::rename($new_table, $old_table);

        DB::table('comments')->truncate();
        DB::table('comment_likes')->truncate();

        DB::insert("INSERT INTO  `tp_comments` (`chapter_id` , `user_id` , `content` , `status` , `likes` , `created_at`) 
                SELECT `post_id` , `uid` , `content` , `status` , `zan` , DATE_FORMAT(FROM_UNIXTIME(`createtime`),'%Y-%m-%d %H:%i:%s') FROM `tp_comments_backup` ");

        DB::insert("INSERT INTO  `tp_comment_likes` (`comment_id` , `user_id` , `created_at`) 
        SELECT `comment_id` , `uid` , DATE_FORMAT(FROM_UNIXTIME(`addtime`),'%Y-%m-%d %H:%i:%s') FROM `tp_comment_zan` ");


        $this->line('数据表复写到新表已完成');


        return 0;
    }
}
