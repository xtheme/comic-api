<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class UserFavoriteLog extends BaseModel
{
    protected $fillable = [
        'user_id',
        'type',
        'item_model',
        'item_id',
    ];

    public function book(): HasOne
    {
        return $this->hasOne('App\Models\Book', 'id', 'item_id');
    }

    public function relation(): HasOne
    {
        return $this->hasOne($this->item_model, 'id', 'item_id');
    }

    public function getItem()
    {
        return $this->relation->findOrFail($this->item_id);
    }

    public function getItemTypeAttribute()
    {
        return __('model.' . $this->type);
    }

    public function getItemTitleAttribute(): string
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
