<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookTagsConvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:book_tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移漫画标签数据!';

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
        // 每次轉詞數據量
        $batch_num = 1000;

        // 每多少筆切割一次操作
        $chunk_num = 50;

        // 新表最後 uid
        $has_old_data = Book::whereNull('updated_at')->first()->exists();

        if ($has_old_data) {
            // 分割集合
            $data = Book::whereNull('updated_at')->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            // 數據批次
            $data->each(function($items, $key) {
                // 漫畫標籤轉換
                $items->each(function($item) {
                    $cate_id = trim($item->cate_id);
                    $cate_arr = explode(',', $cate_id);
                    $tags = DB::table('category')->whereIn('id', $cate_arr)->get()->pluck('name');
                    $book = Book::findOrFail($item->id);
                    $book->attachTags($tags, 'book');

                    // update updated_at
                    $book->touch();
                });

                $this->line('第 ' . ($key + 1) . ' 批標籤数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $pending_num = Book::whereNull('updated_at')->count();

            if ($this->confirm('尚有' . $pending_num . '筆數據等待遷移，是否繼續執行此腳本？')) {
                $this->call('migrate:book_tags');
            } else {
                $this->line('操作已結束');
            }
        } else {
            $this->line('目前沒有等待遷移的數據，操作已結束');
        }

        return 0;
    }
}
