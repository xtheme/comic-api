<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReportType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'operator',
        'operator_id',
        'sort',
        'status',
        'created_at',
        'updated_at'
    ];

    public function admin()
    {
        return $this->hasOne('App\Models\Admin', 'id', 'operator_id');
    }


    public function getstatusTypeAttribute ()
    {
        switch ($this->status) {
            case 0:
                return '<span class="text-success">使用中</span>';
            case 1:
                return '<span class="text-danger">已下架</span>';
            default:
                return '<span class="text-muted">未知</span>';
        }
    }


}
