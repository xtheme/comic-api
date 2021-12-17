<?php

namespace App\Jobs;

use App\Models\Book;
use App\Traits\SendSentry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CheckChapterImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendSentry;

    private $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function handle()
    {
        if (Storage::missing($this->book->getRawOriginal('vertical_cover'))) {
            Log::warning('Book#' . $this->book->id . ' vertical_cover : ' . Storage::url($this->book->getRawOriginal('vertical_cover')) . ' 不存在');
            return true;
        }

        if (Storage::missing($this->book->getRawOriginal('horizontal_cover'))) {
            Log::warning('Book#' . $this->book->id . ' horizontal_cover : ' . Storage::url($this->book->getRawOriginal('horizontal_cover')) . ' 不存在');
            return true;
        }

        $chapters = $this->book->chapters;

        $chapters->each(function ($chapter) {
            $book_id = $chapter->book_id;
            $chapter_id = $chapter->id;
            $images = $chapter->json_images;
            collect($images)->each(function ($image) use ($book_id, $chapter_id) {
                if (Storage::missing($image)) {
                    Log::warning('Book#' . $book_id . ', Chapter#' . $chapter_id . ':' . Storage::url($image) . ' 不存在');
                    return true;
                }
                return true;
            });
        });

        $this->book->update(['review' => 1]);

        Log::warning('Book#' . $this->book->id . ' 圖片完整');

        return true;
    }
}
