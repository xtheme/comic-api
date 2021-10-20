<?php

namespace App\Models;

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

    public function getItem()
    {
        return app($this->item_model)->findOrFail($this->item_id);
    }

    public function getEventAttribute()
    {
        switch ($this->type) {
            case 'book':
                $event = '漫画';
                break;
            case 'book_chapter':
                $event = '漫画章节';
                break;
            case 'video':
                $event = '视频';
                break;
        }

        return $event;
    }

    public function getItemTitleAttribute()
    {
        $item = $this->getItem();

        switch ($this->type) {
            case 'video':
            case 'book':
                $title = $item->title;
                break;
            case 'book_chapter':
                $title = $item->book->title . ' (第 ' . $item->episode . ' 話)';
                break;

        }

        return $title;
    }
}
