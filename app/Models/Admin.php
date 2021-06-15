<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

// todo Admin 重構
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    protected $table = 'admin';

    // todo User 欄位調整
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'status',
        'nickname',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'salt',
        'image',
        'token',
    ];

    public function getLogintimeAttribute($value)
    {
        if (!$value) return '';
        return Carbon::createFromTimeStamp($value);
    }

    public function getCreateTimeAttribute($value)
    {
        if (!$value) return '';
        return Carbon::createFromTimeStamp($value);
    }

    public function getUpdateTimeAttribute($value)
    {
        if (!$value) return '';
        return Carbon::createFromTimeStamp($value);
    }
}
