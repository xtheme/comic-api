<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'uid');
    }

    public function bookchapter()
    {
        return $this->hasOne('App\Models\BookChapter', 'id', 'post_id');
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
