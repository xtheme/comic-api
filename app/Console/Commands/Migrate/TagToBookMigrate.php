<?php

namespace App\Console\Commands\Migrate;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TagToBookMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tag_to_book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将推荐分类标题转换为标签赋予到关联的漫画';

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
        // 推荐分类
        $topics = DB::table('recom_icon')->where('display', 1)->get();

        $topics->map(function ($topic) {
            $title = $topic->title;

            // 縮短標籤
            $contains = Str::contains($topic->title, '~');
            if ($contains) {
                $title = explode('~', $topic->title)[0];
            }

            $contains = Str::contains($topic->title, '-');
            if ($contains) {
                $title = explode('-', $topic->title)[0];
            }

            $title = trim($title);

            // 分類關聯的漫畫
            $book_ids = DB::table('recomlist')->where('type', $topic->id)->pluck('book_id');

            $books = Book::whereIn('id', $book_ids)->get();

            foreach ($books as $book) {
                $book->tag($title);
            }

            $this->line($title . ' 已关联');
        });

        $this->line('数据迁移完成');

        return 0;
    }
}
