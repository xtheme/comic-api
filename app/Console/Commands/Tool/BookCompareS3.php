<?php

namespace App\Console\Commands\Tool;

use App\Models\Book;
use Illuminate\Console\Command;

class BookCompareS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:com:s3';

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
        $books = Book::where('status', 1)->latest()->get('id');

        $books->each(function($book) {

        });

        $this->info($books);
        return 0;
    }
}
