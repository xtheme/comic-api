<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use Illuminate\Console\Command;

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
     * @return void
     */
    public function handle()
    {
        $books = Book::all();
        
        $books->each(function ($book) {
            $keywords = [];

            if ($book->type == 1) {
                $keywords[] = '日漫';
            }

            if ($book->type == 2) {
                $keywords[] = '韩漫';
            }

            if ($book->type == 3) {
                $keywords[] = '美漫';
            }

            if ($book->type == 4) {
                $keywords[] = '写真';
            }

            if ($book->type == 5) {
                $keywords[] = 'CG';
            }

            if ($book->end == 1) {
                $keywords[] = '完结';
            }

            $book->attachTags($keywords, 'book');

            $book->keywords = join(',', $keywords);
            $book->save();

            $this->line('#' . $book->id . '标签已更新!');
        });


        $this->line('操作已結束');
    }
}
