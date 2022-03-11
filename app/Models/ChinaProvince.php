<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChinaProvince extends Model
{
    use HasFactory;

    public function cities(): HasMany
    {
        return $this->hasMany('App\Models\ChinaCity', 'province_id', 'province_id');
    }
}
