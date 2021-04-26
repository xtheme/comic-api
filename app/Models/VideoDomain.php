<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class VideoDomain extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'domain',
        'encrypt_domain',
        'remark',
        'sort',
        'status',
    ];

    public function series()
    {
        return $this->hasMany('App\Models\VideoSeries');
    }
}
