<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use App\Models\BookChapter;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
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
            // DB::table('taggables')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // 只轉移已審核且完結的漫畫
        $this->hasReview();

        return 0;
    }

    public function hasReview()
    {
        $book_condition = [
            ['book_status', '=', 1],
            ['check_status', '=', 1],
            ['app_show', '=', 1],
        ];

        // 符合條件的漫畫筆數
        $batch_num = DB::table('book')->where($book_condition)->count() ?? 0;

        // 每多少筆漫畫拆分一次操作
        $chunk_num = 200;

        $this->line(sprintf('本次操作將轉移 %s 筆數據，拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

        // 审核成功
        $raw = DB::table('book')->where('book_status', 1)->where('check_status', 1)->orderBy('id')->get()->chunk($chunk_num);

        // 數據批次
        $raw->each(function($items, $key) {
            // 漫畫數據轉換
            $items->each(function($item) {
                $chapter_condition = [
                    ['book_id', '=', $item->id],
                    ['status', '=', 1],
                    ['check_status', '<', 2],
                ];

                $has_chapter = DB::table('chapterlist')->where($chapter_condition)->count();

                if (!$has_chapter) {
                    $this->line('第 ' . $item->id . ' 本漫畫缺少章節, 略過');
                    return;
                }

                $chinese = new Chinese();
                $title = $chinese->to(Chinese::ZH_HANS, $item->book_name);
                $author = $chinese->to(Chinese::ZH_HANS, $item->pen_name);
                $description = $chinese->to(Chinese::ZH_HANS, $item->book_desc);

                if (Book::where('title', 'LIKE', '%' . $title . '%')->exists()) {
                    $this->line('第 ' . $item->id . ' 本漫畫標題已存在於數據庫中, 略過');
                    return;
                }

                $insert = [
                    'title' => $title,
                    'author' => $author,
                    'description' => $description,
                    'end' => $item->book_isend == 1 ? 1 : 0,
                    'vertical_cover' => $item->book_thumb,
                    'horizontal_cover' => $item->book_thumb2,
                    'type' => $item->cartoon_type,
                    'status' => $item->book_status ? 1 : 0,
                    'review' => $item->check_status,
                    'operating' => $item->operating,
                    'source_id' => $item->id,
                ];

                $book = Book::firstOrCreate($insert);

                if ($book->wasRecentlyCreated) {
                    $book_id =$book->id;

                    $chapters = DB::table('chapterlist')->where($chapter_condition)->orderBy('id')->get();

                    $insert = $chapters->map(function($item) use ($book_id) {
                        if ($item->json_images) {
                            $json_images = json_decode($item->json_images);
                            $json_images = collect($json_images)->map(function($img) {
                                return $img->url;
                            })->toArray();
                        } else {
                            $json_images = parseImgFromHtml($item->content);
                        }
                        return [
                            'book_id' => $book_id,
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
