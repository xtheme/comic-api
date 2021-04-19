<?php

namespace App\Repositories;

use App\Models\BookChapter;
use App\Repositories\Contracts\BookChapterRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BookChapterRepository extends Repository implements BookChapterRepositoryInterface
{
    protected $cache_ttl = 60; // 缓存秒数

    /**
     * @return string
     */
    public function model(): string
    {
        return BookChapter::class;
    }

    /**
     * @param $book_id
     *
     * @return Builder
     */
    public function filter($book_id): Builder
    {
        return $this->model::where('book_id', $book_id)->latest('id');
    }
}
