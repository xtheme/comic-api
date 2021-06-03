<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    protected $table = 'admin';

    // todo User 欄位調整
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'logintime',
        'loginip',
        'status',
        'nickname',
        'create_time',
        'update_time',
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

    protected $dates = [
        'logintime',
        'create_time',
        'update_time'
    ];

    protected $casts = [
        'logintime' => 'datetime',
        'create_time' => 'datetime',
        'update_time' => 'datetime'
    ];
}
