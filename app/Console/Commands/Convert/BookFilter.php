<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use Illuminate\Console\Command;

class BookFilter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filter:books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '下架沒有章節的漫畫!';


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
        Book::doesntHave('chapters')->update(['status' => 0]);

        $this->info('Done');

        return 0;
    }
}
