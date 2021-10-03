<?php

namespace App\Models;

class Ranking extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'book_id',
        'views',
        'month',
    ];

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }
}
