<?php

namespace App\Models;

class Comment extends BaseModel
{

    protected $table = 'comments_clone';

    protected $fillable = [
        'chapter_id',
        'user_id',
        'content',
        'status',
        'likes',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->select('id' , 'username', 'avatar');
    }

    public function book_chapter()
    {
        return $this->hasOne('App\Models\BookChapter', 'id', 'chapter_id');
    }

    public function getStatusTextAttribute ()
    {

        switch ($this->status) {
            case 1:
                return '<span class="text-success">已通过</span>';
            case 0:
                return '<span class="text-muted">待审核</span>';
            case 2:
                return '<span class="text-danger">已拒绝</span>';
            default:
                return '<span class="text-muted">未知</span>';
        }
    }
}
