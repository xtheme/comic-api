<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
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

            Comment::where('id', $commentLike->comment_id)->update([
                'likes' => DB::raw("likes + 1")
            ]);
        });
    }

}
