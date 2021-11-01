<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BooksConvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將数据表 book 数据将迁移至新表 books!';

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
        $batch_num = 10000;

        // 每多少筆切割一次操作
        $chunk_num = 500;

        // 新舊數據表名稱
        $old_table = 'book';

        // 目前用戶表最後 uid
        $old_primary_id = DB::table($old_table)->orderByDesc('id')->first()->id ?? 0;

        // 新表最後 uid
        $new_primary_id = Book::orderByDesc('id')->first()->id ?? 0;

        if ($old_primary_id > $new_primary_id) {
            // 分割集合
            $data = DB::table($old_table)->where('id', '>', $new_primary_id)->orderBy('id')->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            // 數據批次
            $data->each(function($items, $key) {
                // 漫畫數據轉換
                $insert = $items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->book_name,
                        'author' => $item->pen_name,
                        'description' => $item->book_desc,
                        'end' => $item->book_isend == 1 ? 1 : 0,
                        'vertical_cover' => $item->book_thumb,
                        'horizontal_cover' => $item->book_thumb2,
                        'type' => $item->cartoon_type,
                        'status' => $item->book_status ? 1 : 0,
                        'review' => $item->check_status + 1,
                        'operating' => $item->operating,
                        'view_counts' => $item->view,
                        'collect_counts' => $item->collect,
                        'created_at' => $item->book_addtime ? Carbon::createFromTimestamp($item->book_addtime) : null,
                        'updated_at' => null,
                    ];
                })->toArray();

                Book::insert($insert);

                $this->line('第 ' . ($key + 1) . ' 批漫畫数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $latest_primary_id = Book::orderByDesc('id')->first()->id;

            $pending_num = DB::table($old_table)->where('id', '>', $latest_primary_id)->count();

            if ($this->confirm('尚有' . $pending_num . '筆數據等待遷移，是否繼續執行此腳本？')) {
                $this->call('migrate:books');
            } else {
                $this->line('操作已結束');
            }
        } else {
            $this->line('目前沒有等待遷移的數據，操作已結束');
        }

        return 0;
    }
}
