<?php

namespace App\Models;

use App\Enums\NavigationOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasFactory;

    protected $table = 'navigation';

    protected $fillable = [
        'title',
        'icon',
        'uri',
        'target',
        'sort',
        'status',
    ];

    // public function getIconAttribute($value)
    // {
    //     return getImageDomain() . $value;
    // }

    public function getTypeAttribute()
    {
        $type_options = NavigationOptions::TYPE_OPTIONS;

        foreach($type_options as $key => $val) {
            if ($val == $this->uri) return $key;
        }

        return '自定义链接';
    }

    public function getActiveAttribute()
    {
        return NavigationOptions::STATUS_OPTIONS[$this->status];
    }

    public function getTargetAttribute($value)
    {
        return NavigationOptions::TARGET_OPTIONS[$value];
    }
}
