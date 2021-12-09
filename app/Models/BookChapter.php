<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookChapter extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'json_images' => 'array',
    ];

    public function book()
    {
        return $this->belongsTo('App\Models\Book');
    }

    public function purchase_log()
    {
        return $this->hasOne('App\Models\UserPurchaseLog', 'item_id', 'id')->where('item_model', get_class($this));
    }

    // 將 json_images 字段中的圖片路徑加上資源域名, 如果使用加密資源則指定圖片寬度
    public function getContentAttribute()
    {
        $images = $this->json_images ?? [];

        foreach ($images as $key => $image) {
            if (Str::startsWith($image, '/') ) {
                $image = substr($image, 1);
            }
            $images[$key] = getImageUrl($image);
        }

        return $images;
    }

    public function setJsonImagesAttribute($images)
    {
        foreach ($images as $image) {
            if (!parse_url($image)['path']) {
                continue;
            }
            $array[] = parse_url($image)['path'];
        }

        $this->attributes['json_images'] = json_encode($array);
    }

    public function getPrevChapterIdAttribute()
    {
        $chapter = self::where('book_id', $this->book_id)->where('episode', '<', $this->episode)->orderByDesc('episode')->first(['id']);

        return $chapter->id ?? '';
    }

    public function getNextChapterIdAttribute()
    {
        $chapter = self::where('book_id', $this->book_id)->where('episode', '>', $this->episode)->orderBy('episode')->first(['id']);

        return $chapter->id ?? '';
    }
}
