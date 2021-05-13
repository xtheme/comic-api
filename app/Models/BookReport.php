<?php

namespace App\Models;

class BookReport extends BaseModel
{
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }

    public function report_type()
    {
        return $this->hasOne('App\Models\BookReportType', 'id', 'book_report_type_id');
    }
}
