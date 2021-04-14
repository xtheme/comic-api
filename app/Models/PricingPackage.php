<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PricingPackage
 *
 * @property int $id
 * @property string $type 套餐类型
 * @property string $name 名称
 * @property string $label 标签
 * @property int $days 有效天数
 * @property string $price 售价
 * @property string $list_price 牌价 (原价)
 * @property int $status 状态, 0=关闭, 1=新用户, 2=老用户
 * @property int $sort 显示排序
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read mixed $pack_status
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage query()
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereListPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PricingPackage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PricingPackage extends Model
{
    use HasFactory;
    protected $table = 'pricing_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'label',
        'days',
        'price',
        'list_price',
        'status',
        'sort',
        'created_at',
        'updated_at',
    ];



    public function getPackStatusAttribute ()
    {
        $status = [-1 => '禁用', 0 => '全部用户', 1 => '新用户', 2 => '老用户'];
        return $status[$this->status];
    }

}
