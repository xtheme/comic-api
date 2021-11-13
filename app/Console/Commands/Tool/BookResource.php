<?php

namespace App\Console\Commands\Tool;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BookResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'book:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '獲取漫畫圖片路徑';

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
        $books = Book::with(['chapters'])->where('status', 1)->take(2000)->latest()->get();

        $books->each(function($book) {
            Storage::disk('local')->append('resource.txt', $book->getRawOriginal('vertical_cover'));
            Storage::disk('local')->append('resource.txt', $book->getRawOriginal('horizontal_cover'));
            $chapters = $book->chapters;
            $chapters->each(function ($chapter) {
                $images = $chapter->json_images;
                collect($images)->each(function ($image) {
                    Storage::disk('local')->append('resource.txt', $image);
                });
            });
            $this->info($book->id . ' done');
        });

        $this->info('All done');
        return 0;
    }
}
