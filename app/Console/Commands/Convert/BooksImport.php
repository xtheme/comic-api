<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SteelyWing\Chinese\Chinese;

class BooksImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:import';

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
        if ($this->confirm('是否清空舊數據')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('books')->truncate();
            DB::table('book_chapters')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // 只轉移已審核且完結的漫畫
        $this->hasReview();

        return 0;
    }

    public function hasReview()
    {
        $book_condition = [
            ['status', '=', 1],
        ];

        // 符合條件的漫畫筆數
        $batch_num = DB::table('source_books')->where($book_condition)->count() ?? 0;

        // 每多少筆漫畫拆分一次操作
        $chunk_num = 200;

        $this->line(sprintf('本次操作將轉移 %s 筆數據，拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

        // 审核成功
        $raw = DB::table('source_books')->where('status', 1)->orderBy('id')->get()->chunk($chunk_num);

        // 數據批次
        $raw->each(function ($items, $key) {
            // 漫畫數據轉換
            $items->each(function ($item) {
                $chapter_condition = [
                    ['book_id', '=', $item->id],
                    ['status', '=', 1],
                    ['review', '=', 1],
                ];

                $has_chapter = DB::table('source_book_chapters')->where($chapter_condition)->count();

                if (!$has_chapter) {
                    $this->line('第 ' . $item->id . ' 本漫畫缺少章節, 略過');
                    return;
                }

                $chinese = new Chinese();
                $title = $chinese->to(Chinese::ZH_HANS, $item->title);
                $author = $chinese->to(Chinese::ZH_HANS, $item->author);
                $description = $chinese->to(Chinese::ZH_HANS, $item->description);

                if (Book::where('title', 'LIKE', '%' . $title . '%')->exists()) {
                    $this->line('第 ' . $item->id . ' 本漫畫標題已存在於數據庫中, 略過');
                    return;
                }

                $insert = [
                    'title' => $title,
                    'author' => $author,
                    'description' => $description,
                    'end' => $item->end == 1 ? 1 : 0, // 完结: 1=完结, 0=未完结
                    'cover' => $item->vertical_cover,
                    'type' => $item->type, // 类型: 1=日漫, 2=韩漫, 3=美漫, 4=写真, 5=CG
                    'status' => $item->status == 1 ? 1 : 0, // 状态: 1=上架, 0=下架
                    'review' => $item->review + 1, // 审核状态: 1=待审核, 2=审核成功, 3=审核失败, 4=屏蔽, 5=未审核
                    'operating' => $item->operating, // 添加方式: 1=人工, 2= 爬虫
                    'source_platform' => 'nasu',
                    'source_id' => $item->id,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'deleted_at' => $item->deleted_at,
                ];

                $book = Book::firstOrCreate($insert);

                if ($book->wasRecentlyCreated) {
                    $book_id = $book->id;

                    $chapters = DB::table('source_book_chapters')->where($chapter_condition)->orderBy('id')->get();

                    $insert = $chapters->map(function ($item) use ($book_id) {
                        return [
                            'book_id' => $book_id,
                            'episode' => $item->episode,
                            'title' => $item->title,
                            'json_images' => $item->json_images,
                            'status' => $item->status ? 1 : 0,
                            'price' => $item->episode > 2 ? 5 : 0,
                            'operating' => $item->operating, // 添加方式: 1=人工, 2= 爬虫
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                            'deleted_at' => $item->deleted_at,
                        ];
                    })->toArray();

                    BookChapter::insert($insert);

                    $this->line('第 ' . $item->id . ' 本漫畫章節已建立');
                } else {
                    $this->line('第 ' . $item->id . ' 本漫畫已存在, 略過');
                    return;
                }

                $this->line('第 ' . $book_id . ' 本漫畫完成');
            });

            $this->line('第 ' . ($key + 1) . ' 批漫畫数据迁移完成...');
        });

        $this->line('數據已全數遷移！');
    }
}
