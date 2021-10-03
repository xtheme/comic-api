<?php

namespace App\Models;

use App\Casts\BooleanCast;

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
        'json_images' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    //組織json_images 完整路徑
    public function getJsonImageThumbAttribute()
    {
        $images = $this->json_images;
        
        foreach ($images as $key => $image) {
            $images[$key] = getConfig('app', 'img_url') . $image;
        }

        return $images;
    }
}
