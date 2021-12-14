<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    use HasFactory;

    public function options()
    {
        return $this->hasMany('App\Models\FeedbackOption', 'question_id');
    }
}
