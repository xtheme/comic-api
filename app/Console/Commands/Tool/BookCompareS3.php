<?php

namespace App\Console\Commands\Tool;

use App\Jobs\CheckChapterImages;
use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookCompareS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:checks3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '排查目前漫畫數據庫中的圖片路徑有那些能跟S3匹配上, 如圖片不存在標記為下架';

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
        // 將所有漫畫標記為圖片不完整
        DB::table('books')->update(['review' => 2]);

        $count = DB::table('books')->count();

        $this->info('共有' . $count . '本漫畫進行檢查');

        $books = Book::with(['chapters'])->latest()->get();

        $books->each(function($book) {
            CheckChapterImages::dispatch($book);
        });

        $count = DB::table('books')->where('review', 1)->count();

        $this->info('共有' . $count . '本漫畫存在於S3');

        return 0;
    }
}
