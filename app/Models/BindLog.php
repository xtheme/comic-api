<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BindLog
 *
 * @property string|null $mobile 電話
 * @property string $device_user_id 裝置 user_id
 * @property int $action 操作行為，1:綁定，2:解綁，3:後台解綁
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|BindLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BindLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BindLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|BindLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BindLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BindLog whereDeviceUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BindLog whereMobile($value)
 * @mixin \Eloquent
 */
class BindLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile',
        'device_user_id',
        'action',
    ];
}
