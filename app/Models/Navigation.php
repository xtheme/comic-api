<?php

namespace App\Models;

use App\Enums\NavigationOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Navigation extends Model
{
    use HasFactory;

    protected $table = 'navigation';

    protected $fillable = [
        'title',
        'icon',
        'target',
        'filter_id',
        'link',
        'sort',
        'status',
    ];

    // 篩選規則
    public function filter()
    {
        return $this->hasOne('App\Models\Filter', 'id', 'filter_id');
    }

    public function getActiveAttribute()
    {
        return NavigationOptions::STATUS_OPTIONS[$this->status];
    }

    public function getTargetAttribute($value)
    {
        return NavigationOptions::TARGET_OPTIONS[$value];
    }

    public function getIconAttribute($value)
    {
        if (!$value) return '';

        return getImageUrl($value);
    }
}
