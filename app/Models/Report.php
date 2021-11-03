<?php

namespace App\Models;

class Report extends BaseModel
{
    const UPDATED_AT = null;

    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

    // public function book()
    // {
    //     return $this->hasOne('App\Models\Book', 'id', 'book_id');
    // }

    public function issue()
    {
        return $this->hasOne('App\Models\ReportIssue');
    }
}
