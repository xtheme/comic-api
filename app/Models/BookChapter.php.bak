<?php

namespace App\Models;

class BookChapter extends BaseModel
{
    protected $table = 'chapterlist';

    protected $perPage = 10;

    const CREATED_AT = 'addtime';
    const UPDATED_AT = 'updatetime';

    protected $casts = [
        'json_images' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }
}
