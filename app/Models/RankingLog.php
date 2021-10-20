<?php

namespace App\Models;

class RankingLog extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'item_model',
        'item_id',
        'views',
        'year',
        'month',
    ];

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'item_id');
    }
}
