<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LadyCity extends Model
{
    use HasFactory;

    public $hidden = [
        'p_id',
        'layer',
        'sort',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'p_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'p_id', 'id')->orderBy('sort');
    }

    public function lady()
    {
        return $this->belongsTo('App\Models\Lady');
    }
}
