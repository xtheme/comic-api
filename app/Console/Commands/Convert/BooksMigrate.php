<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BooksMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:books {--id=*}';

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
        $chunk_num = 100;

        // 目前用戶表最後 uid
        $old_primary_id = DB::table('book')->where('check_status', '!=', 3)->orderByDesc('id')->first()->id ?? 0;

        // 新表最後 uid
        $new_primary_id = $this->option('id') ?? 0;
        // $new_primary_id = Book::orderByDesc('id')->first()->id ?? 0;

        if ($old_primary_id > $new_primary_id) {
            // 分割集合
            $raw = DB::table('book')->where('book_status', 1)->where('check_status', 1)->where('id', '>', $new_primary_id)->orderBy('id')->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            // 數據批次
            $raw->each(function($items, $key) {
                // 漫畫數據轉換
                $items->each(function($item) {
                    $has_chapter = DB::table('chapterlist')->where('book_id', $item->id)->where('status', 1)->where('check_status', 1)->count();

                    if (!$has_chapter) {
                        $this->line('第 ' . $item->id . ' 本漫畫缺少章節, 略過');
                        return;
                    }

                    $data = [
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
                        'source_id' => $item->id,
                        'view_counts' => 0,
                        'collect_counts' => 0,
                        'created_at' => now(),
                    ];

                    $book = Book::create($data);

                    $chapters = DB::table('chapterlist')->where('book_id', $item->id)->where('status', 1)->where('check_status', 1)->orderBy('id')->get();

                    $insert = $chapters->map(function($item) use ($book) {
                        if ($item->json_images) {
                            $json_images = json_decode($item->json_images);
                            $json_images = collect($json_images)->map(function($img) {
                                return $img->url;
                            })->toArray();
                        } else {
                            $json_images = parseImgFromHtml($item->content);
                        }
                        return [
                            'book_id' => $book->id,
                            'episode' => $item->idx,
                            'title' => $item->title,
                            'content' => '',
                            'json_images' => json_encode($json_images),
                            'status' => $item->status ? 1 : 0,
                            'price' => $item->idx > 2 ? 60 : 0,
                            'operating' => $item->operating,
                            'created_at' => $item->addtime ? Carbon::createFromTimestamp($item->addtime) : null,
                            'updated_at' => null,
                        ];
                    })->toArray();

                    BookChapter::insert($insert);

                    $this->line('第 ' . $book->id . ' 本漫畫完成');
                });

                $this->line('第 ' . ($key + 1) . ' 批漫畫数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $latest_primary_id = Book::orderByDesc('id')->first()->id;

            $pending_num = DB::table('book')->where('id', '>', $latest_primary_id)->count();

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