<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id'
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function ($commentLike) {
            $commentLike->comment->increment('likes');
        });
    }

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment');
    }

}
