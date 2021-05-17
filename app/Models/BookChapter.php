<?php

namespace App\Models;

class BookChapter extends BaseModel
{
    protected $perPage = 10;

    protected $fillable = [
        'book_id',
        'episode',
        'title',
        'content',
        'json_images',
        'status',
        'charge',
        'review',
        'operating',
    ];

    protected $casts = [
        // 'json_images' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }
}
