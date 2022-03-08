<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class UserPurchaseLog extends BaseModel
{
    protected $fillable = [
        'user_id',
        'app_id',
        'channel_id',
        'type',
        'item_model',
        'item_id',
        'item_price',
    ];

    public function book(): HasOne
    {
        return $this->hasOne('App\Models\Book', 'id', 'item_id');
    }

    public function book_chapter(): HasOne
    {
        return $this->hasOne('App\Models\BookChapter', 'id', 'item_id');
    }

    public function video(): HasOne
    {
        return $this->hasOne('App\Models\Video', 'id', 'item_id');
    }

    public function getItemTypeAttribute()
    {
        return __('model.' . $this->type);
    }

    public function getItemTitleAttribute()
    {
        switch ($this->type) {
            case 'video':
                $title = $this->video->title;
                break;
            case 'book':
                $title = $this->book->title;
                break;
            case 'book_chapter':
                $title = $this->book_chapter->book->title . ' (第 ' . $this->book_chapter->episode . ' 話)';
                break;
            default:
                $title = '';
                break;
        }

        return $title;
    }
}
