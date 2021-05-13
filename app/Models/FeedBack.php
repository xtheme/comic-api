<?php

namespace App\Models;

class FeedBack extends BaseModel
{
    protected $table = 'feedback';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'uid');
    }



}
