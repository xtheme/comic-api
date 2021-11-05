<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use Illuminate\Console\Command;

class BookRelation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:relation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '關聯漫畫標籤!';


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
        $books = Book::where('end', 1)->get();

        $books->each(function($book) {
            $book->attachTag('完结', 'book');
        });

        //-------------

        $books = Book::where('title', 'LIKE', '%汉化%')->get();

        $books->update([
            'type' => 1
        ]);

        $books->each(function($book) {
            $book->attachTag('汉化', 'book');
        });

        //-------------

        $books = Book::where('title', 'LIKE', '%翻訳%')->get();

        $books->update([
            'type' => 1
        ]);

        $books->each(function($book) {
            $book->attachTag('汉化', 'book');
        });

        //-------------

        $books = Book::where('title', 'LIKE', '%写真%')->get();

        $books->update([
            'type' => 4
        ]);

        $books->each(function($book) {
            $book->attachTag('写真', 'book');
        });

        //-------------

        $books = Book::where('title', 'LIKE', '%CG%')->get();

        $books->update([
            'type' => 5
        ]);

        $books->each(function($book) {
            $book->attachTag('CG', 'book');
        });

        //-------------

        $books = Book::where('title', 'LIKE', '%同人%')->get();

        $books->each(function($book) {
            $book->attachTag('同人', 'book');
        });

        $this->info('Done');

        return 0;
    }
}
