<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BookCategory
 *
 * @property int $id
 * @property string|null $name 分类名称
 * @property int|null $parentid 父级id
 * @property int|null $orders 排序
 * @property string|null $keywords 分类关键词
 * @property string|null $desc 分类描述
 * @property int|null $status 1表示显示,0表示隐藏
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory whereOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory whereParentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookCategory whereStatus($value)
 * @mixin \Eloquent
 */
class BookCategory extends Model
{
    use HasFactory;

    protected $table = 'category';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'parentid',
        'orders',
        'keywords',
        'desc',
        'status',
    ];
}
