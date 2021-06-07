<?php

namespace App\Models;

class BookFavorite extends BaseModel
{
    protected $fillable = [
        'book_id',
        'chapter_id',
        'user_id',
    ];

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }

    public function chapter()
    {
        return $this->hasOne('App\Models\BookChapter', 'id', 'chapter_id');
    }
}
