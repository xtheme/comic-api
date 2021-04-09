<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
