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
}
