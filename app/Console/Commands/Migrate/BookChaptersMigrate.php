<?php

namespace App\Console\Commands\Migrate;

ini_set('memory_limit', '-1');

use App\Models\BookChapter;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookChaptersMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:book_chapters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將数据表 chapterlist 数据将迁移至新表 book_chapters!';

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
        $old_table = 'chapterlist';
        $new_table = 'book_chapters';

        // 目前用戶表最後 uid
        $old_primary_id = DB::table($old_table)->orderByDesc('id')->first()->id;

        // 新表最後 uid
        $new_primary_id = DB::table($new_table)->orderByDesc('id')->first()->id;

        if ($old_primary_id > $new_primary_id) {
            // 分割集合
            $data = DB::table($old_table)->where('id', '>', $new_primary_id)->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            $data->each(function($items, $key) use ($new_table) {
                $insert = $items->map(function($item) use ($new_table) {
                    return [
                        'id' => $item->id,
                        'book_id' => $item->book_id,
                        'episode' => $item->idx,
                        'title' => $item->title,
                        'content' => $item->content,
                        'json_images' => $item->json_images ?? parseImgFromHtml($item->content),
                        'status' => $item->status ? 1 : -1,
                        'charge' => $item->isvip ? 1 : -1,
                        'review' => $item->check_status + 1,
                        'operating' => $item->operating,
                        'created_at' => $item->addtime ? Carbon::createFromTimestamp($item->addtime) : null,
                        'updated_at' => $item->updatetime ? Carbon::createFromTimestamp($item->updatetime) : null,
                    ];
                })->toArray();

                DB::table($new_table)->insert($insert);

                $this->line('第 ' . ($key + 1) . ' 批数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $latest_primary_id = DB::table($new_table)->orderByDesc('id')->first()->id;

            $pending_num = DB::table($old_table)->where('id', '>', $latest_primary_id)->count();

            if ($this->confirm('尚有' . $pending_num . '筆數據等待遷移，是否繼續執行此腳本？')) {
                $this->call('migrate:book_chapters');
            } else {
                $this->line('操作已結束');
            }
        } else {
            $this->line('目前沒有等待遷移的數據，操作已結束');
        }

        return 0;
    }
}
