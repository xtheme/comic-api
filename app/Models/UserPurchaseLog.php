<?php

namespace App\Models;

class UserPurchaseLog extends BaseModel
{
    public function getProduct()
    {
        return app($this->item_model)->findOrFail($this->item_id);
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

    public function getItemTitleAttribute()
    {
        $product = $this->getProduct();

        switch ($this->type) {
            case 'book_chapter':
                $title = $product->book->title . ' (第 ' . $product->episode . ' 話)';
                break;
            case 'video':
                $title = $product->title;
                break;
        }

        return $title;
    }
}
