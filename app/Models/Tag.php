<?php

namespace App\Models;

use Conner\Tagging\Model\Tagged;

class Tag extends \Conner\Tagging\Model\Tag
{
    // public $appends = [
    //     'related_book_count',
    //     'related_video_count',
    // ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($tag) {

            if ($tag->isDirty('slug')) {
                Tagged::where('tag_name', $tag->getOriginal('slug'))->update([
                    'tag_name' => $tag->slug,
                    'tag_slug' => $tag->slug,
                ]);
            }
        });

        static::deleted(function ($tag) {
            Tagged::where('tag_name' , $tag->slug)->delete();
        });

    }

    public function tagged_book()
    {
        return $this->hasMany('Conner\Tagging\Model\Tagged', 'tag_name', 'name')->where('taggable_type', 'App\Models\Book');
    }

    public function tagged_video()
    {
        return $this->hasMany('Conner\Tagging\Model\Tagged', 'tag_name', 'name')->where('taggable_type', 'App\Models\Video');
    }

    // public function getRelatedBookCountAttribute()
    // {
    //     return $this->tagged_book->count();
    // }
    //
    // public function getRelatedVideoCountAttribute()
    // {
    //     return $this->tagged_video->count();
    // }
}
