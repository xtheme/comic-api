<?php

namespace App\Models;

class ReportIssue extends BaseModel
{
    protected $fillable = [
        'name',
        'sort',
        'status',
        'created_at',
        'updated_at',
    ];

    public function getStatusTypeAttribute()
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
