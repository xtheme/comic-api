<?php

namespace App\Console\Commands\Refactor;

use App\Models\Book;
use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\TagGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CategoryRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rollback:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '回滚 tagging_tags & tagging_tagged 两个表到初始状态!';

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
        if ($this->confirm('请确认是否运行回滚脚本? 执行且请先备份数据表!')) {

            DB::table('tagging_tags')->truncate();
            DB::table('tagging_tagged')->truncate();

            $this->line('tagging_tags & tagging_tagged 回滚完成');
        }

        return 0;
    }
}
