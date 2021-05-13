<?php

namespace App\Models;

class Notice extends BaseModel
{
    protected $table = 'notice';

    public $timestamps = false;

    protected $fillable = [
        'notice_title',
        'notice_keyword',
        'notice_content',
        'dyestuff',
        'edition_type',
        'copy_content',
        'is_copy',
        'time',
    ];

    public function getTypeAttribute()
    {
        switch ($this->edition_type) {
            case 2:
                return '<span class="text-success">新版</span>';
            case 1:
                return '<span class="text-danger">旧版</span>';
        }
    }
}
