<?php

namespace App\Models;

use App\Traits\CanPurchase;

class BookChapter extends BaseModel
{
    use CanPurchase;

    protected $perPage = 10;

    protected $fillable = [
        'book_id',
        'episode',
        'title',
        'json_images',
        'status',
        'price',
        'operating',
    ];

    protected $casts = [
        'json_images' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    // 將 json_images 字段中的圖片路徑加上資源域名, 如果使用加密資源則指定圖片寬度
    public function getContentAttribute()
    {
        $images = $this->json_images;

        foreach ($images as $key => $image) {
            $images[$key] = getImageDomain() . $image;
        }

        return $images;
    }

    public function setJsonImagesAttribute($images)
    {
        foreach ($images as $key => $image) {
            if (!parse_url($image)['path']) {
                continue;
            }
            $array[] = parse_url($image)['path'];
        }

        $this->attributes['json_images'] = json_encode($array);
    }
}
