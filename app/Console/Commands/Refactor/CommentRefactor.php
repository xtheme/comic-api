<?php

namespace App\Console\Commands\Refactor;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CommentRefactor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:comments';

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

        DB::table('comments_clone')->truncate();
        DB::table('comment_likes')->truncate();

        DB::insert("INSERT INTO  `tp_comments_clone` (`chapter_id` , `user_id` , `content` , `status` , `likes` , `created_at`) 
                SELECT `post_id` , `uid` , `content` , `status` , `zan` , DATE_FORMAT(FROM_UNIXTIME(`createtime`),'%Y-%m-%d %H:%i:%s') FROM `tp_comments` ");

        DB::insert("INSERT INTO  `tp_comment_likes` (`comment_id` , `user_id` , `created_at`) 
        SELECT `comment_id` , `uid` , DATE_FORMAT(FROM_UNIXTIME(`addtime`),'%Y-%m-%d %H:%i:%s') FROM `tp_comment_zan` ");


        $this->line('数据表复写到新表已完成');


        return 0;
    }
}
