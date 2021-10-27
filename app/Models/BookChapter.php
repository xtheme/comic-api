<?php

namespace App\Models;

class BookChapter extends BaseModel
{
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

    public function purchase_log()
    {
        return $this->hasOne('App\Models\UserPurchaseLog', 'item_id', 'id')->where('item_model', get_class($this));
    }


    public function getPurchasedAttribute()
    {
        $user = auth('sanctum')->user() ?? null;

        if (!$user) return false;

        if ($user->is_vip) {
            return true;
        }

        return $user->purchase_logs()->where('type', 'book_chapter')->where('item_id', $this->id)->exists();
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
