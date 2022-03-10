<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChinaCity extends Model
{
    use HasFactory;

    public function areas(): HasMany
    {
        return $this->hasMany('App\Models\ChinaArea', 'city_id', 'city_id');
    }
}
