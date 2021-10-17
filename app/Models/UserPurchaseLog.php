<?php

namespace App\Models;

class UserPurchaseLog extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function book_chappter()
    {
        return $this->hasOne('App\Models\BookChapter', 'id', 'item_id');
    }

    public function getEventAttribute()
    {
        switch ($this->type) {
            case 'book_chapter':
                $event = '漫画章节';
                break;
            case 'video':
                $event = '视频';
                break;
        }

        return $event;
    }

    public function getTitleAttribute()
    {
        switch ($this->type) {
            case 'book_chapter':
                $title = $this->book_chappter->book->title . ' (第 ' .$this->book_chappter->episode . ' 話)';
                break;
            case 'video':
                $title = '视频';
                break;
        }

        return $title;
    }
}
